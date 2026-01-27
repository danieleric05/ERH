<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ShowSanctionErrors extends Command
{
    protected $signature = 'sanctions:errors {lines? : Nombre de lignes de logs à afficher}';
    protected $description = 'Afficher les erreurs de la dernière importation sanctions';

    public function handle(): int
    {
        $lines = $this->argument('lines') ?? 500;
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            $this->error('Fichier log non trouvé');
            return 1;
        }

        // Lire dernières lignes du log
        $logs = $this->tail($logFile, $lines);

        // Filtrer pour trouver les erreurs d'importation sanctions
        $importStart = -1;
        foreach ($logs as $i => $line) {
            if (strpos($line, 'RÉSUMÉ IMPORTATION SANCTIONS') !== false) {
                $importStart = $i;
                break;
            }
        }

        if ($importStart === -1) {
            $this->error('Aucune importation sanctions trouvée dans les logs');
            return 1;
        }

        $this->info('📋 ERREURS DE LA DERNIÈRE IMPORTATION SANCTIONS\n');

        // Afficher à partir du début de l'importation
        $errors = [];
        $inErrors = false;

        for ($i = $importStart; $i < count($logs); $i++) {
            $line = $logs[$i];

            // Détection section résumé
            if (strpos($line, 'RÉSUMÉ IMPORTATION') !== false) {
                $inErrors = false;
                $this->line("<info>" . trim(strip_tags($line)) . "</info>");
            }

            // Détection section erreurs détaillées
            if (strpos($line, 'Erreurs détaillées') !== false) {
                $inErrors = true;
                $this->line("\n<fg=red>❌ ERREURS DÉTAILLÉES</>\n");
                continue;
            }

            // Afficher erreurs
            if ($inErrors && !empty(trim($line))) {
                // Parser ligne d'erreur
                preg_match('/- ([^\s]+)\s+\(([^)]+)\):\s+(.+)/', trim(strip_tags($line)), $matches);
                if (!empty($matches)) {
                    $matricule = $matches[1];
                    $nom = $matches[2];
                    $erreur = $matches[3];

                    $this->line("  <fg=red>$matricule</> ($nom)");
                    $this->line("    → <fg=yellow>$erreur</>\n");

                    $errors[] = ['matricule' => $matricule, 'nom' => $nom, 'erreur' => $erreur];
                } else {
                    // Ligne générale
                    $this->line("  " . trim(strip_tags($line)));
                }
            }
        }

        // Afficher statistiques
        $this->newLine();
        $this->info('📊 STATISTIQUES ERREURS');

        $orphelins = array_filter($errors, fn($e) => strpos($e['erreur'], 'Matricule orphelin') !== false);
        $encodage = array_filter($errors, fn($e) => strpos($e['erreur'], 'Incorrect string value') !== false);

        $this->line("Total erreurs : " . count($errors));
        $this->line("  - Matricules orphelins : " . count($orphelins));
        $this->line("  - Erreurs encodage : " . count($encodage));
        $this->line("  - Autres : " . (count($errors) - count($orphelins) - count($encodage)));

        return 0;
    }

    /**
     * Lire dernières N lignes d'un fichier
     */
    private function tail(string $file, int $lines): array
    {
        $handle = fopen($file, 'r');
        if (!$handle) {
            return [];
        }

        fseek($handle, -1, SEEK_END);
        $result = [];
        $buffer = '';
        $lineCount = 0;

        while ($lineCount < $lines && ftell($handle) > 0) {
            $char = fgetc($handle);
            if ($char === "\n") {
                $lineCount++;
                $result[] = trim($buffer);
                $buffer = '';
            } else {
                $buffer = $char . $buffer;
            }
            fseek($handle, -2, SEEK_CUR);
        }

        if (!empty($buffer)) {
            $result[] = trim($buffer);
        }

        fclose($handle);
        return array_reverse($result);
    }
}
