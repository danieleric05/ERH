<?php

namespace App\Imports;

use App\Sanctions;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SanctionImport implements ToCollection, WithHeadingRow
{
    public $success = 0;
    public $skipped = 0;
    public $errors = [];

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

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Valider ligne non vide
                if ($this->isEmptyRow($row)) {
                    continue;
                }

                // Récupérer travailleur
                $matricule = trim($row['MATLE'] ?? '');
                if (empty($matricule)) {
                    $this->addError($row, 'Matricule vide');
                    continue;
                }

                $travailleur = Travailleur::where('matricule', $matricule)->first();
                if (!$travailleur) {
                    $this->addError($row, "Matricule orphelin: {$matricule}");
                    continue;
                }

                // Vérifier dates obligatoires
                $dateSanction = $this->parseDate($row['date courrier'] ?? null);
                if (!$dateSanction) {
                    $this->addError($row, 'Date courrier vide ou invalide');
                    continue;
                }

                // Vérifier si sanction déjà existante (doublon)
                $verif = Sanctions::where('employeid', $matricule)
                    ->where('datesanction', $dateSanction)
                    ->first();

                if ($verif) {
                    $this->skipped++;
                    Log::info("Sanction dupliquée ignorée: {$matricule} du {$dateSanction}");
                    continue;
                }

                // Normaliser type sanction
                $sanctionType = trim($row['Sanction'] ?? '');
                $normalizedSanction = $this->normalizeSanction($sanctionType);

                // Si sanction vide ou non trouvée, on peut la passer en attente
                if (empty($sanctionType)) {
                    $this->skipped++;
                    Log::warning("Sanction vide pour {$matricule}");
                    continue;
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
                $sanction->demandeurid = auth()->id() ?? 1; // Utilisateur courant
                $sanction->employeid = $matricule;
                $sanction->motif = 0; // Par défaut (peut référencer table e_motif si elle existe)
                $sanction->expose_motif = trim($row['Motif'] ?? '');
                $sanction->datesanction = $dateSanction;

                // Date du fait fautif = date courrier si pas spécifiée
                $dateFautes = $this->parseDate($row['Date Sanction'] ?? null);
                $sanction->datefautes = $dateFautes ?? $dateSanction;

                $sanction->sanction_applique = $normalizedSanction;
                $sanction->nombre_jour = $nombreJours;

                // Début = date notification ou date sanction
                $dateNotification = $this->parseDate($row['Date Notification'] ?? null);
                $sanction->debut = $dateNotification ?? $dateSanction;
                $sanction->fin = $fin;

                $sanction->quart = 0; // Pas d'info quart dans CSV
                $sanction->statutid = 1; // Actif
                $sanction->userid = auth()->id() ?? 1;

                // Extraire année/mois de date sanction
                $carbonDate = Carbon::parse($dateSanction);
                $sanction->mois = $carbonDate->month;
                $sanction->annee = $carbonDate->year;

                $sanction->save();
                $this->success++;

                Log::info("Sanction importée: {$matricule} - Type: {$normalizedSanction}");

            } catch (\Exception $e) {
                $this->addError($row, $e->getMessage());
                Log::error("Erreur importation sanction: {$e->getMessage()}");
            }
        }

        $this->logSummary();
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
    private function isEmptyRow(array $row): bool
    {
        return empty($row['MATLE']) && empty($row['Nom']) && empty($row['Sanction']);
    }

    /**
     * Ajouter une erreur au log
     */
    private function addError(array $row, string $error): void
    {
        $this->errors[] = [
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
