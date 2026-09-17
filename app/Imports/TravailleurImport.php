<?php

namespace App\Imports;

use App\Categories;
use App\Commune;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\NiveauEtude;
use App\Pays;
use App\Travailleur;
use App\TypeContrat;
use App\Unites;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TravailleurImport
{
    public $success = 0;
    public $skipped = 0;
    public $errors = [];

    /**
     * Mapping type employeur CSV → e_travailleur.type_employer
     */
    private $employeurMap = [
        'JOURNALIER' => 1,
        'EMBAUCHE' => 2,
        'STAGE' => 3,
    ];

    /**
     * Cache pour les clés étrangères (optimisation N+1)
     */
    private $departements = [];
    private $unites = [];
    private $equipes = [];
    private $categories = [];
    private $communes = [];
    private $fonctions = [];
    private $niveaux = [];
    private $typeContrats = [];
    private $pays = [];

    public function __construct()
    {
        $this->loadReferenceTables();
    }

    /**
     * Charger les tables de référence en cache
     */
    private function loadReferenceTables(): void
    {
        Log::info("📚 Chargement des tables de référence...");

        $this->departements = Departement::pluck('id', 'code')->toArray();
        $this->unites = Unites::pluck('id', 'code')->toArray();
        $this->equipes = Equipes::pluck('id', 'code')->toArray();
        $this->categories = Categories::pluck('id', 'code')->toArray();
        $this->communes = Commune::pluck('id', 'code')->toArray();
        $this->fonctions = Fonction::pluck('id', 'code')->toArray();
        $this->niveaux = NiveauEtude::pluck('id', 'code')->toArray();
        $this->typeContrats = TypeContrat::pluck('id', 'code')->toArray();
        $this->pays = Pays::pluck('id', 'code')->toArray();

        Log::info("✅ Tables de référence chargées: " .
            count($this->departements) . " depts, " .
            count($this->unites) . " unites, " .
            count($this->categories) . " categories"
        );
    }

    /**
     * Importer depuis fichier CSV avec délimiteur ";" et encodage ISO-8859-1
     */
    public function importFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier non trouvé: {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Impossible d'ouvrir le fichier: {$filePath}");
        }

        // Configurer encodage ISO-8859-1 → UTF-8
        stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');

        // Lire en-têtes (première ligne)
        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            fclose($handle);
            throw new \Exception("Fichier CSV vide ou non lisible");
        }

        // Normaliser en-têtes (trim)
        $headers = array_map('trim', $headers);
        Log::info("📋 En-têtes CSV détectées (" . count($headers) . "): " . json_encode($headers));

        $lineNumber = 1;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $lineNumber++;

            // Construire array associatif
            $row = array_combine($headers, array_pad($data, count($headers), null));
            if ($row === false) {
                continue;
            }

            // Traiter la ligne
            $this->processRow($row, $lineNumber);
        }

        fclose($handle);
        $this->logSummary();
    }

    /**
     * Traiter une ligne individuelle
     */
    private function processRow(array $row, int $lineNumber): void
    {
        try {
            // Valider ligne non vide
            if ($this->isEmptyRow($row)) {
                return;
            }

            // Récupérer et valider matricule (obligatoire)
            $matricule = trim($row['MATLE'] ?? $row['Matricule'] ?? '');
            if (empty($matricule)) {
                $this->addError($row, 'Matricule vide', $lineNumber);
                return;
            }

            // Vérifier doublon (même matricule déjà importé)
            $existing = Travailleur::where('matricule', $matricule)->first();
            if ($existing) {
                $this->skipped++;
                Log::warning("Travailleur dupliqué ignoré: {$matricule}");
                return;
            }

            // Valider données obligatoires
            $nom = trim($row['NOM'] ?? $row['Nom'] ?? '');
            if (empty($nom)) {
                $this->addError($row, 'Nom vide', $lineNumber);
                return;
            }

            // Créer le travailleur
            $travailleur = new Travailleur();
            $travailleur->matricule = $matricule;

            // Identité
            $travailleur->civilite = trim($row['CIVILITE'] ?? $row['Civilité'] ?? 'M');
            $travailleur->nom = $nom;
            $travailleur->prenom = trim($row['PRENOM'] ?? $row['Prénom'] ?? '');
            $travailleur->prenom_suite = trim($row['PRENOM_SUITE'] ?? '');

            // Dates
            $travailleur->date_naissance = $this->parseDate($row['DATE_NAISSANCE'] ?? $row['Date Naissance'] ?? null);
            $travailleur->lieu_naissance = trim($row['LIEU_NAISSANCE'] ?? $row['Lieu Naissance'] ?? '');

            // Informations de contrat
            $travailleur->date_debut_contrat = $this->parseDate($row['DATE_DEBUT_CONTRAT'] ?? $row['Date Debut'] ?? null);
            $travailleur->date_fin_contrat = $this->parseDate($row['DATE_FIN_CONTRAT'] ?? $row['Date Fin'] ?? null);
            $travailleur->motif_fin_contrat = trim($row['MOTIF_FIN_CONTRAT'] ?? '');

            // Type employeur / contrat
            $typeEmployeur = trim($row['TYPE_EMPLOYER'] ?? $row['Type Employer'] ?? 'EMBAUCHE');
            $travailleur->type_employer = $this->normalizeEmployeurType($typeEmployeur);

            $typeContratCode = trim($row['TYPE_CONTRAT'] ?? $row['Type Contrat'] ?? '');
            $travailleur->idtype_contrat = $this->resolveFK('typeContrats', $typeContratCode);

            // Organisation
            $depCode = trim($row['DEPARTEMENT'] ?? $row['Département'] ?? '');
            $travailleur->departementid = $this->resolveFK('departements', $depCode);

            $uniCode = trim($row['UNITE'] ?? $row['Unité'] ?? '');
            $travailleur->uniteid = $this->resolveFK('unites', $uniCode);

            $eqpCode = trim($row['EQUIPE'] ?? $row['Équipe'] ?? '');
            $travailleur->equipeid = $this->resolveFK('equipes', $eqpCode);

            // Catégorie et fonction
            $catCode = trim($row['CATEGORIE'] ?? $row['Catégorie'] ?? '');
            $travailleur->categorieid = $this->resolveFK('categories', $catCode);

            $fonCode = trim($row['FONCTION'] ?? $row['Fonction'] ?? '');
            $travailleur->fonction_entrepriseid = $this->resolveFK('fonctions', $fonCode);

            // Contact et localisation
            $travailleur->email = trim($row['EMAIL'] ?? '');
            $travailleur->telephone = trim($row['TELEPHONE'] ?? $row['Téléphone'] ?? '');
            $travailleur->telephone2 = trim($row['TELEPHONE2'] ?? $row['Téléphone 2'] ?? '');

            $nationCode = trim($row['NATIONALITE'] ?? $row['Nationalité'] ?? '');
            $travailleur->nationaliteid = $this->resolveFK('pays', $nationCode);

            $commCode = trim($row['COMMUNE'] ?? $row['Commune'] ?? '');
            $travailleur->communeid = $this->resolveFK('communes', $commCode);

            // Niveau études et informations complémentaires
            $niveCode = trim($row['NIVEAU_ETUDE'] ?? $row['Niveau Étude'] ?? '');
            $travailleur->niveau_etudeid = $this->resolveFK('niveaux', $niveCode);

            // Sécurité sociale et documents
            $travailleur->numero_securite = trim($row['NUMERO_SECURITE'] ?? $row['Numéro Sécurité'] ?? '');
            $travailleur->situation_mat = trim($row['SITUATION_MAT'] ?? $row['Situation Mat'] ?? '');
            $travailleur->nombre_enfant = trim($row['NOMBRE_ENFANT'] ?? $row['Nombre Enfants'] ?? '');

            // Identifiant
            $travailleur->identifiant = trim($row['IDENTIFIANT'] ?? $row['Identifiant'] ?? '');

            // Paie
            $travailleur->type_salaire = (int) ($row['TYPE_SALAIRE'] ?? 1);
            $travailleur->bulletin_modele_salarie = (int) ($row['BULLETIN_MODELE'] ?? 0);

            // Description et statut
            $travailleur->description = trim($row['DESCRIPTION'] ?? '');
            $travailleur->statutid = 1; // Actif par défaut
            $travailleur->userid = auth()->id() ?? 1;

            // Métadonnées
            $travailleur->inscrit_le = Carbon::now()->toDateString();
            $travailleur->mois = Carbon::now()->month;
            $travailleur->annee = Carbon::now()->year;

            // Sauvegarder
            $travailleur->save();
            $this->success++;

            Log::info("✅ Travailleur importé ligne {$lineNumber}: {$matricule} ({$nom})");

        } catch (\Exception $e) {
            $this->addError($row, $e->getMessage(), $lineNumber);
            Log::error("❌ Erreur ligne {$lineNumber}: {$e->getMessage()}");
        }
    }

    /**
     * Parser une date au format JJ/MM/YYYY, YYYY-MM-DD ou similaire
     */
    private function parseDate(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        $dateString = trim($dateString);

        try {
            // Essayer format JJ/MM/YYYY
            $carbon = Carbon::createFromFormat('d/m/Y', $dateString);
            return $carbon->toDateString();
        } catch (\Exception $e1) {
            try {
                // Essayer format YYYY-MM-DD
                $carbon = Carbon::createFromFormat('Y-m-d', $dateString);
                return $carbon->toDateString();
            } catch (\Exception $e2) {
                try {
                    // Essayer format JJ/MM/YY
                    $carbon = Carbon::createFromFormat('d/m/y', $dateString);
                    return $carbon->toDateString();
                } catch (\Exception $e3) {
                    Log::warning("Date invalide: '{$dateString}'");
                    return null;
                }
            }
        }
    }

    /**
     * Normaliser type employeur vers code unifié
     */
    private function normalizeEmployeurType(string $type): int
    {
        $type = strtoupper(trim($type));

        // Correspondance directe
        foreach ($this->employeurMap as $pattern => $code) {
            if (strpos($type, $pattern) !== false) {
                return $code;
            }
        }

        // Par défaut: EMBAUCHE
        return 2;
    }

    /**
     * Résoudre une clé étrangère via cache (code → id)
     * Retourne null si non trouvé
     */
    private function resolveFK(string $table, ?string $code): ?int
    {
        if (empty($code)) {
            return null;
        }

        $code = strtoupper(trim($code));
        $cache = &$this->$table;

        return $cache[$code] ?? null;
    }

    /**
     * Vérifier si une ligne est vide
     */
    private function isEmptyRow($row): bool
    {
        $matricule = trim($row['MATLE'] ?? $row['Matricule'] ?? '');
        $nom = trim($row['NOM'] ?? $row['Nom'] ?? '');

        return empty($matricule) && empty($nom);
    }

    /**
     * Ajouter une erreur au log
     */
    private function addError($row, string $error, int $lineNumber): void
    {
        $this->errors[] = [
            'ligne' => $lineNumber,
            'matricule' => $row['MATLE'] ?? $row['Matricule'] ?? 'N/A',
            'nom' => $row['NOM'] ?? $row['Nom'] ?? 'N/A',
            'erreur' => $error,
        ];
    }

    /**
     * Afficher résumé importation
     */
    private function logSummary(): void
    {
        Log::info("=== RÉSUMÉ IMPORTATION TRAVAILLEURS ===");
        Log::info("✅ Importés: {$this->success}");
        Log::info("⏭️  Ignorés: {$this->skipped}");
        Log::info("❌ Erreurs: " . count($this->errors));

        if (!empty($this->errors)) {
            Log::error("Erreurs détaillées (premiers 20):");
            foreach (array_slice($this->errors, 0, 20) as $error) {
                Log::error(
                    "  Ligne {$error['ligne']}: {$error['matricule']} ({$error['nom']}) - {$error['erreur']}"
                );
            }
            if (count($this->errors) > 20) {
                Log::error("  ... et " . (count($this->errors) - 20) . " autres erreurs");
            }
        }
    }

    /**
     * Retourner résumé pour affichage utilisateur
     */
    public function getSummary(): array
    {
        return [
            'success' => $this->success,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'total' => $this->success + $this->skipped + count($this->errors),
        ];
    }
}
