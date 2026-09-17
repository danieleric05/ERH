<?php

namespace App\Imports;

use App\Sanctions;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SanctionImport
{
    public $success = 0;
    public $skipped = 0;
    public $errors = [];
    private $demandeurCache = [];  // Cache pour les demandeurs

    /**
     * Mapping types sanctions CSV → ENT (e_sanction.sanction_applique)
     */
    private $sanctionMap = [
        'AVERT' => 1,
        'AVERTISSEMENT' => 1,
        'AVRT' => 1,
        'MAP-01JR' => 2,
        'MAP-1 JOUR' => 2,
        'MAP-1JR' => 2,
        'MAP-1 JOURS' => 2,
        'MAP-02JRS' => 3,
        'MAP-2 JOURS' => 3,
        'MAP-2JRS' => 3,
        'MAP-2 Jours' => 3,
        'MAP-2 JOURS' => 3,
        'MAP-2JOURS' => 3,
        'MAP-3 JOURS' => 4,
        'MAP-3JRS' => 4,
        'MAP-3JOURS' => 4,
        'MAP-04 JOURS' => 4,
        'MAP-4 JOURS' => 4,
        'MAP-4JRS' => 4,
        'MAP-5 JOURS' => 5,
        'MAP-5JRS' => 5,
        'MAP-5JOURS' => 5,
        'MAP-7 JOURS' => 6,
        'MAP-7JRS' => 6,
        'MAP-7JOURS' => 6,
        'LICENCIEMENT' => 7,
    ];

    /**
     * Importer depuis fichier CSV avec délimiteur personnalisé (;)
     */
    public function importFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Fichier non trouvé: {$filePath}");
        }

        // Lire avec encodage UTF-8
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Impossible d'ouvrir le fichier: {$filePath}");
        }

        // Configurer encodage pour fgetcsv
        stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');

        // Lire en-têtes (première ligne)
        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            fclose($handle);
            throw new \Exception("Fichier CSV vide ou non lisible");
        }

        // Normaliser en-têtes (trim)
        $headers = array_map('trim', $headers);
        Log::info("En-têtes CSV: " . json_encode($headers));

        // Détecter colonnes optionnelles
        $hasDemandeur = in_array('Demandeur', $headers);
        $hasQuart = in_array('Quart', $headers);
        $hasStatut = in_array('Statut', $headers);

        Log::info("Colonnes optionnelles détectées - Demandeur: " . ($hasDemandeur ? 'OUI' : 'NON') .
                  ", Quart: " . ($hasQuart ? 'OUI' : 'NON') . ", Statut: " . ($hasStatut ? 'OUI' : 'NON'));

        $lineNumber = 1;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $lineNumber++;

            // Construire array associatif
            $row = array_combine($headers, array_pad($data, count($headers), null));
            if ($row === false) {
                continue;
            }

            // Traiter la ligne avec info colonnes optionnelles
            $this->processRow($row, $lineNumber, [
                'hasDemandeur' => $hasDemandeur,
                'hasQuart' => $hasQuart,
                'hasStatut' => $hasStatut
            ]);
        }

        fclose($handle);
        $this->logSummary();
    }

    /**
     * Traiter une ligne individuelle
     */
    private function processRow(array $row, int $lineNumber, array $optionalColumns = []): void
    {
        try {
            // Valider ligne non vide
            if ($this->isEmptyRow($row)) {
                return;
            }

            // Récupérer travailleur
            $matricule = trim($row['MATLE'] ?? '');
            if (empty($matricule)) {
                $this->addError($row, 'Matricule vide', $lineNumber);
                return;
            }

            $travailleur = Travailleur::where('matricule', $matricule)->first();
            if (!$travailleur) {
                $this->addError($row, "Matricule orphelin: {$matricule}", $lineNumber);
                return;
            }

            // Vérifier dates obligatoires
            $dateSanction = $this->parseDate($row['date courrier'] ?? null);
            if (!$dateSanction) {
                $this->addError($row, 'Date courrier vide ou invalide', $lineNumber);
                return;
            }

            // Vérifier si sanction déjà existante (doublon)
            $verif = Sanctions::where('employeid', $matricule)
                ->where('datesanction', $dateSanction)
                ->first();

            if ($verif) {
                $this->skipped++;
                Log::info("Sanction dupliquée ignorée: {$matricule} du {$dateSanction}");
                return;
            }

            // Normaliser type sanction
            $sanctionType = trim($row['Sanction'] ?? '');
            $normalizedSanction = $this->normalizeSanction($sanctionType);

            // Si sanction vide ou non trouvée, ignorer
            if (empty($sanctionType)) {
                $this->skipped++;
                Log::warning("Sanction vide pour {$matricule}");
                return;
            }

            // Extraire nombre de jours
            $nombreJours = $this->extractDays($sanctionType);

            // Calculer fin de sanction
            $fin = null;
            if ($nombreJours > 0 && $dateSanction) {
                $fin = Carbon::parse($dateSanction)->addDays($nombreJours);
            }

            // Créer enregistrement
            $sanction = new Sanctions();

            // === DEMANDEUR ===
            if ($optionalColumns['hasDemandeur'] ?? false) {
                $demandeurName = trim($row['Demandeur'] ?? '');
                $demandeur = $this->findDemandeur($demandeurName);
                $sanction->demandeurid = $demandeur ? $demandeur->id : (auth()->id() ?? 1);
            } else {
                $sanction->demandeurid = auth()->id() ?? 1;
            }

            $sanction->employeid = $matricule;
            $sanction->motif = 0;
            $sanction->expose_motif = trim($row['Motif'] ?? '');
            $sanction->datesanction = $dateSanction;

            // Date du fait fautif
            $dateFautes = $this->parseDate($row['Date Sanction'] ?? null);
            $sanction->datefautes = $dateFautes ?? $dateSanction;

            $sanction->sanction_applique = $normalizedSanction;
            $sanction->nombre_jour = $nombreJours;

            // Début = date notification ou date sanction
            $dateNotification = $this->parseDate($row['Date Notification'] ?? null);
            $sanction->debut = $dateNotification ?? $dateSanction;
            $sanction->fin = $fin;

            // === QUART ===
            if ($optionalColumns['hasQuart'] ?? false) {
                $quart = trim($row['Quart'] ?? '0');
                $sanction->quart = is_numeric($quart) ? (int)$quart : 0;
            } else {
                $sanction->quart = 0;
            }

            // === STATUT ===
            if ($optionalColumns['hasStatut'] ?? false) {
                $statut = trim($row['Statut'] ?? 'Enregistré');
                // Mapper valeurs possibles: Enregistré = 1, Ajouté aux variables = 2
                $sanction->statutid = ($statut === 'Ajouté aux variables' || $statut === '2') ? 2 : 1;
            } else {
                $sanction->statutid = 1;
            }

            $sanction->userid = auth()->id() ?? 1;

            // Extraire année/mois
            $carbonDate = Carbon::parse($dateSanction);
            $sanction->mois = $carbonDate->month;
            $sanction->annee = $carbonDate->year;

            $sanction->save();
            $this->success++;

            Log::info("Sanction importée ligne {$lineNumber}: {$matricule} - Type: {$normalizedSanction}");

        } catch (\Exception $e) {
            $this->addError($row, $e->getMessage(), $lineNumber);
            Log::error("Erreur ligne {$lineNumber}: {$e->getMessage()}");
        }
    }

    /**
     * Chercher un demandeur par nom/prénom avec cache
     */
    private function findDemandeur(string $name): ?Travailleur
    {
        if (empty($name)) {
            return null;
        }

        $name = trim($name);

        // Vérifier le cache
        if (isset($this->demandeurCache[$name])) {
            return $this->demandeurCache[$name];
        }

        // Chercher en base
        $demandeur = Travailleur::where('nom', 'like', '%' . $name . '%')
            ->orWhere('prenom', 'like', '%' . $name . '%')
            ->first();

        // Stocker dans le cache (même si null)
        $this->demandeurCache[$name] = $demandeur;

        return $demandeur;
    }

    /**
     * Parser une date au format JJ/MM/YYYY ou la retourner NULL
     */
    private function parseDate($dateString): ?Carbon
    {
        if (empty($dateString) || !is_string($dateString)) {
            return null;
        }

        $dateString = trim($dateString);

        // Essayer format JJ/MM/YYYY
        try {
            return Carbon::createFromFormat('d/m/Y', $dateString);
        } catch (\Exception $e) {
            // Essayer format YYYY-MM-DD
            try {
                return Carbon::createFromFormat('Y-m-d', $dateString);
            } catch (\Exception $e2) {
                Log::warning("Date invalide: {$dateString}");
                return null;
            }
        }
    }

    /**
     * Normaliser type sanction vers code unifié
     */
    private function normalizeSanction(string $sanction): ?int
    {
        $sanction = trim(strtoupper($sanction));

        // Correspondance directe
        if (isset($this->sanctionMap[$sanction])) {
            return $this->sanctionMap[$sanction];
        }

        // Recherche approximative (contient)
        foreach ($this->sanctionMap as $pattern => $code) {
            if (strpos($sanction, str_replace('-', '', $pattern)) !== false) {
                return $code;
            }
        }

        Log::warning("Type sanction non reconnu: {$sanction}");
        return null;
    }

    /**
     * Extraire nombre de jours d'une sanction de type "MAP-2 JOURS"
     */
    private function extractDays(string $sanction): int
    {
        if (preg_match('/(\d+)/', $sanction, $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }

    /**
     * Vérifier si une ligne est vide
     */
    private function isEmptyRow($row): bool
    {
        if ($row instanceof Collection) {
            $row = $row->toArray();
        }

        return empty($row['MATLE'] ?? null) && empty($row['Nom'] ?? null) && empty($row['Sanction'] ?? null);
    }

    /**
     * Ajouter une erreur au log
     */
    private function addError($row, string $error, int $lineNumber = 0): void
    {
        if ($row instanceof Collection) {
            $row = $row->toArray();
        }

        $this->errors[] = [
            'ligne' => $lineNumber,
            'matricule' => $row['MATLE'] ?? 'N/A',
            'nom' => $row['Nom'] ?? 'N/A',
            'erreur' => $error,
        ];
    }

    /**
     * Afficher résumé importation
     */
    private function logSummary(): void
    {
        Log::info("=== RÉSUMÉ IMPORTATION SANCTIONS ===");
        Log::info("✅ Importées: {$this->success}");
        Log::info("⏭️  Ignorées: {$this->skipped}");
        Log::info("❌ Erreurs: " . count($this->errors));

        if (!empty($this->errors)) {
            Log::error("Erreurs détaillées:");
            foreach (array_slice($this->errors, 0, 10) as $error) {
                Log::error("  - {$error['matricule']} ({$error['nom']}): {$error['erreur']}");
            }
            if (count($this->errors) > 10) {
                Log::error("  ... et " . (count($this->errors) - 10) . " autres erreurs");
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
