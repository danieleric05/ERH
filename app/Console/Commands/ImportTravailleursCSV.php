<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Travailleur;
use App\Fonction;
use App\Unites;
use Illuminate\Support\Facades\DB;

class ImportTravailleursCSV extends Command
{
    protected $signature = 'import:travailleurs {file : Chemin vers le fichier CSV} {--dry-run : Simuler sans importer}';
    protected $description = 'Importe des travailleurs depuis un fichier CSV';

    // Mapping des fonctions CSV vers IDs en base
    private $fonctionMapping = [];

    // Mapping des unités CSV vers IDs en base
    private $uniteMapping = [];

    // Mapping situation matrimoniale
    private $situationMapping = [
        'CELIBATAIRE' => 'Célibataire',
        'MARIEE' => 'Marié(e)',
        'MARIE' => 'Marié(e)',
        'DIVORCE' => 'Divorcé(e)',
        'DIVORCEE' => 'Divorcé(e)',
        'VEUF' => 'Veuf(ve)',
        'VEUVE' => 'Veuf(ve)',
    ];

    // Mapping civilité
    private $civiliteMapping = [
        'Monsieur' => 'M.',
        'Madame' => 'Mme',
        'Mademoiselle' => 'Mlle',
    ];

    public function handle()
    {
        $filePath = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("Le fichier n'existe pas: $filePath");
            return 1;
        }

        $this->info("Lecture du fichier: $filePath");
        if ($dryRun) {
            $this->warn("MODE SIMULATION - Aucune donnée ne sera importée");
        }

        // Charger les mappings depuis la base
        $this->loadMappings();

        // Lire le CSV
        $rows = $this->parseCSV($filePath);

        if (empty($rows)) {
            $this->error("Aucune donnée trouvée dans le fichier");
            return 1;
        }

        $this->info("Nombre de lignes à traiter: " . count($rows));

        $created = 0;
        $updated = 0;
        $errors = 0;

        $this->output->progressStart(count($rows));

        foreach ($rows as $index => $row) {
            try {
                $result = $this->processRow($row, $dryRun);
                if ($result === 'created') {
                    $created++;
                } elseif ($result === 'updated') {
                    $updated++;
                }
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Erreur ligne " . ($index + 2) . ": " . $e->getMessage());
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->newLine();
        $this->info("=== Résumé ===");
        $this->info("Créés: $created");
        $this->info("Mis à jour: $updated");
        if ($errors > 0) {
            $this->error("Erreurs: $errors");
        }

        return 0;
    }

    private function loadMappings()
    {
        // Charger les fonctions
        $fonctions = Fonction::all();
        foreach ($fonctions as $f) {
            $this->fonctionMapping[strtoupper(trim($f->label))] = $f->id;
        }

        // Ajouter des alias pour les fonctions
        $this->fonctionMapping['COUTURIERE'] = $this->fonctionMapping['COUTURIER'] ?? null;

        // Charger les unités
        $unites = Unites::all();
        foreach ($unites as $u) {
            $this->uniteMapping[strtoupper(trim($u->label))] = $u->id;
        }

        // Ajouter des alias pour les unités PP RAFFIA
        $ppRaffiaId = $this->uniteMapping['PP RAFFIA'] ?? 1;
        $this->uniteMapping['PP RAFFIA A'] = $ppRaffiaId;
        $this->uniteMapping['PP RAFFIA B'] = $ppRaffiaId;
        $this->uniteMapping['PP RAFFIA C'] = $ppRaffiaId;
        $this->uniteMapping['PP  RAFFIA A'] = $ppRaffiaId; // double espace
        $this->uniteMapping['PP  RAFFIA C'] = $ppRaffiaId;
        $this->uniteMapping['PP RAF FIA A'] = $ppRaffiaId; // espace dans RAFFIA
        $this->uniteMapping['PP RAFFIA'] = $ppRaffiaId;

        $this->info("Fonctions chargées: " . count($this->fonctionMapping));
        $this->info("Unités chargées: " . count($this->uniteMapping));
    }

    private function parseCSV($filePath)
    {
        $rows = [];
        $handle = fopen($filePath, 'r');

        // Lire l'en-tête
        $header = fgetcsv($handle, 0, ';');

        // Normaliser les noms de colonnes (gérer l'encodage)
        $header = array_map(function($col) {
            return $this->normalizeColumnName($col);
        }, $header);

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            // Ignorer les lignes vides
            if (empty(array_filter($data))) {
                continue;
            }

            // Créer un tableau associatif
            $row = [];
            foreach ($header as $i => $col) {
                $row[$col] = isset($data[$i]) ? trim($data[$i]) : '';
            }

            // Vérifier que le matricule existe
            if (!empty($row['Matricule'])) {
                $rows[] = $row;
            }
        }

        fclose($handle);
        return $rows;
    }

    private function normalizeColumnName($name)
    {
        // Mapping des colonnes avec encodage problématique vers noms normalisés
        $mappings = [
            'Civilit' => 'Civilite',
            'CNPS' => 'CNPS',
            'phone' => 'Telephone',
            'but' => 'DateDebut',
            'gorie' => 'Categorie',
        ];

        // Normaliser le nom
        $normalized = trim($name);

        // Chercher des correspondances partielles
        if (stripos($normalized, 'Civilit') !== false) return 'Civilite';
        if (stripos($normalized, 'CNPS') !== false) return 'CNPS';
        if (stripos($normalized, 'phone') !== false) return 'Telephone';
        if (stripos($normalized, 'DateD') !== false && stripos($normalized, 'but') !== false) return 'DateDebut';
        if (stripos($normalized, 'gorie') !== false) return 'Categorie';
        if (stripos($normalized, 'Pi') !== false && stripos($normalized, 'ce') !== false) return 'NaturePiece';

        return $normalized;
    }

    private function processRow($row, $dryRun)
    {
        $matricule = strtoupper(trim($row['Matricule']));

        // Vérifier si le travailleur existe
        $travailleur = Travailleur::where('matricule', $matricule)->first();
        $isNew = ($travailleur === null);

        if ($isNew) {
            $travailleur = new Travailleur();
            $travailleur->matricule = $matricule;
        }

        // Mapper les données (utiliser les noms normalisés)
        $travailleur->civilite = $this->civiliteMapping[$row['Civilite'] ?? ''] ?? null;
        $travailleur->nom = strtoupper(trim($row['NOM'] ?? ''));

        // Gérer prénom (peut être dans PRENOMS)
        $prenoms = trim($row['PRENOMS'] ?? '');
        $prenomParts = explode(' ', $prenoms, 2);
        $travailleur->prenom = strtoupper($prenomParts[0] ?? '');
        $travailleur->prenom_suite = isset($prenomParts[1]) ? strtoupper($prenomParts[1]) : null;

        // Date de naissance
        $dateNaissance = $this->parseDate($row['DateNaissance'] ?? '');
        if ($dateNaissance) {
            $travailleur->date_naissance = $dateNaissance;
        }

        $travailleur->lieu_naissance = strtoupper(trim($row['LieuNaissance'] ?? ''));

        // Situation matrimoniale
        $sitMat = strtoupper(trim($row['Sit.Matrimoniale'] ?? ''));
        $travailleur->situation_mat = $this->situationMapping[$sitMat] ?? $sitMat;

        $travailleur->nombre_enfant = $row['NbreEnfant'] ?? null;

        // CNPS (colonne normalisée)
        $cnps = trim($row['CNPS'] ?? '');
        if (!empty($cnps)) {
            $travailleur->numero_securite = $cnps;
        }

        // Pièce d'identité (CNI)
        $cni = trim($row['CNI'] ?? '');
        if (!empty($cni)) {
            $travailleur->pieceidentite = strtoupper($cni);
        }

        $dateCNI = $this->parseDate($row['DateCNI'] ?? '');
        if ($dateCNI) {
            $travailleur->pieceidentite_livrele = $dateCNI;
        }

        $lieuCNI = trim($row['lieuCNI'] ?? '');
        if (!empty($lieuCNI)) {
            $travailleur->pieceidentite_lieu = strtoupper($lieuCNI);
        }

        // Téléphone (colonne normalisée)
        $tel = preg_replace('/\s+/', '', $row['Telephone'] ?? '');
        if (!empty($tel)) {
            $travailleur->telephone = $tel;
        }

        // Dates contrat (colonne normalisée)
        $dateDebut = $this->parseDate($row['DateDebut'] ?? '');
        if ($dateDebut) {
            $travailleur->date_debut_contrat = $dateDebut;
        }

        $dateFin = $this->parseDate($row['DateFin'] ?? '');
        if ($dateFin) {
            $travailleur->date_fin_contrat = $dateFin;
        }

        // Fonction
        $fonction = strtoupper(trim($row['Fonction'] ?? ''));
        if (!empty($fonction) && isset($this->fonctionMapping[$fonction])) {
            $travailleur->fonction_entrepriseid = $this->fonctionMapping[$fonction];
        }

        // Unité
        $unite = strtoupper(trim($row['UNITE'] ?? ''));
        if (!empty($unite) && isset($this->uniteMapping[$unite])) {
            $travailleur->uniteid = $this->uniteMapping[$unite];
        }

        // Type de contrat (CDD = 2)
        $typeContrat = strtoupper(trim($row['CONTRAT'] ?? ''));
        if ($typeContrat === 'CDD') {
            $travailleur->idtype_contrat = 2;
        }

        // Valeurs par défaut
        $travailleur->statutid = $travailleur->statutid ?? 1;
        $travailleur->type_employer = $travailleur->type_employer ?? 2; // CDD/CDI
        $travailleur->etapeid = $travailleur->etapeid ?? 2; // Profil complet
        $travailleur->nationaliteid = $travailleur->nationaliteid ?? 1; // Côte d'Ivoire par défaut

        if (!$dryRun) {
            $travailleur->save();
        }

        return $isNew ? 'created' : 'updated';
    }

    private function parseDate($dateStr)
    {
        if (empty($dateStr) || $dateStr === '00/01/1900') {
            return null;
        }

        // Format DD/MM/YYYY
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dateStr, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }

        // Format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        return null;
    }
}
