<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExportSanctionErrors extends Command
{
    protected $signature = 'sanctions:export-errors {output? : Chemin fichier sortie CSV}';
    protected $description = 'Exporter les erreurs d\'importation sanctions en CSV';

    public function handle(): int
    {
        $output = $this->argument('output') ?? storage_path('imports/sanctions_errors_' . now()->format('Y-m-d_His') . '.csv');

        // Créer dossier s'il n'existe pas
        @mkdir(dirname($output), 0755, true);

        // Lire logs
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            $this->error('Fichier log non trouvé');
            return 1;
        }

        $logs = $this->tail($logFile, 1000);

        // Parser les erreurs
        $errors = [];
        $inErrors = false;

        foreach ($logs as $line) {
            if (strpos($line, 'Erreurs détaillées') !== false) {
                $inErrors = true;
                continue;
            }

            if ($inErrors && preg_match('/- ([^\s]+)\s+\(([^)]+)\):\s+(.+)/', trim(strip_tags($line)), $matches)) {
                $errors[] = [
                    'matricule' => $matches[1],
                    'nom' => $matches[2],
                    'erreur' => $matches[3],
                ];
            }
        }

        if (empty($errors)) {
            $this->error('Aucune erreur trouvée');
            return 1;
        }

        // Écrire CSV
        $handle = fopen($output, 'w');

        // En-têtes
        fputcsv($handle, ['Matricule', 'Nom', 'Type Erreur', 'Message'], ',', '"');

        // Parser type erreur
        foreach ($errors as $error) {
            $typeErreur = 'Autre';
            if (strpos($error['erreur'], 'Matricule orphelin') !== false) {
                $typeErreur = 'Matricule orphelin';
            } elseif (strpos($error['erreur'], 'Incorrect string value') !== false) {
                $typeErreur = 'Encodage';
            } elseif (strpos($error['erreur'], 'Date') !== false) {
                $typeErreur = 'Format date';
            }

            fputcsv($handle, [
                $error['matricule'],
                $error['nom'],
                $typeErreur,
                substr($error['erreur'], 0, 100),
            ], ',', '"');
        }

        fclose($handle);

        $this->info("✅ Erreurs exportées : {$output}");
        $this->info("📊 Total erreurs : " . count($errors));

        return 0;
    }

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
