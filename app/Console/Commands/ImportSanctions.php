<?php

namespace App\Console\Commands;

use App\Imports\SanctionImport;
use Illuminate\Console\Command;

class ImportSanctions extends Command
{
    /**
     * Nom et description de la commande
     */
    protected $signature = 'sanctions:import {file : Chemin du fichier CSV à importer}';

    protected $description = 'Importer les sanctions depuis un fichier CSV';

    /**
     * Exécuter la commande
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');

        // Vérifier existance fichier
        if (!file_exists($filePath)) {
            $this->error("❌ Fichier non trouvé: {$filePath}");
            return 1;
        }

        $this->info("📂 Fichier détecté: {$filePath}");
        $this->line("Taille: " . number_format(filesize($filePath) / 1024, 2) . " KB");

        // Confirmation
        if (!$this->confirm('Procéder à l\'importation ?')) {
            $this->info('Importation annulée.');
            return 0;
        }

        // Exécuter importation
        $this->info('⏳ Importation en cours...');
        $startTime = microtime(true);

        try {
            $import = new SanctionImport();
            $import->importFromFile($filePath);

            $duration = round(microtime(true) - $startTime, 2);
            $summary = $import->getSummary();

            // Afficher résumé
            $this->newLine();
            $this->info('✅ Importation terminée en ' . $duration . 's');
            $this->line('');
            $this->info('📊 RÉSUMÉ');
            $this->line('  ✅ Importées : ' . $summary['success']);
            $this->line('  ⏭️  Ignorées : ' . $summary['skipped']);
            $this->line('  ❌ Erreurs : ' . count($summary['errors']));
            $this->line('  ━━━━━━━━━━━━━━━━━');
            $this->line('  📈 Total : ' . $summary['total']);

            if (!empty($summary['errors'])) {
                $this->newLine();
                $this->warn('⚠️  ERREURS DÉTAILLÉES (premiers 15)');
                foreach (array_slice($summary['errors'], 0, 15) as $i => $error) {
                    $lineInfo = isset($error['ligne']) && !empty($error['ligne']) ? "Ligne {$error['ligne']}: " : '';
                    $this->line(($i + 1) . ". {$lineInfo}{$error['matricule']} ({$error['nom']})");
                    $this->line("   → {$error['erreur']}");
                }
                if (count($summary['errors']) > 15) {
                    $this->line("   ... et " . (count($summary['errors']) - 15) . " autres erreurs");
                }
                $this->warn("\n⚠️  Consultez les logs (storage/logs/laravel.log) pour la liste complète des erreurs");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'importation:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}

