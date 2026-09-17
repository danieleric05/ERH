<?php

namespace App\Console\Commands;

use App\Imports\TravailleurImport;
use Illuminate\Console\Command;

class ImportTravailleurs extends Command
{
    /**
     * Nom et description de la commande
     */
    protected $signature = 'travailleurs:import {file : Chemin du fichier CSV à importer}';

    protected $description = 'Importer les travailleurs/embauchés depuis un fichier CSV Sage';

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

        $fileSize = filesize($filePath);
        $this->info("📂 Fichier détecté: {$filePath}");
        $this->line("   Taille: " . number_format($fileSize / 1024, 2) . " KB");

        // Confirmation
        if (!$this->confirm('Procéder à l\'importation ?')) {
            $this->info('⚠️  Importation annulée.');
            return 0;
        }

        // Exécuter importation
        $this->info('⏳ Importation en cours...');
        $startTime = microtime(true);

        try {
            $import = new TravailleurImport();
            $import->importFromFile($filePath);

            $duration = round(microtime(true) - $startTime, 2);
            $summary = $import->getSummary();

            // Afficher résumé
            $this->newLine();
            $this->info('✅ Importation terminée en ' . $duration . 's');
            $this->line('');
            $this->info('📊 RÉSUMÉ');
            $this->line('  ✅ Importés   : ' . $summary['success']);
            $this->line('  ⏭️  Ignorés   : ' . $summary['skipped']);
            $this->line('  ❌ Erreurs   : ' . count($summary['errors']));
            $this->line('  ━━━━━━━━━━━━━━━━━━━');
            $this->line('  📈 Total     : ' . $summary['total']);

            // Afficher détails des erreurs si présentes
            if (!empty($summary['errors'])) {
                $this->newLine();
                $this->warn('⚠️  ERREURS DÉTAILLÉES (premiers 20)');
                foreach (array_slice($summary['errors'], 0, 20) as $i => $error) {
                    $this->line(($i + 1) . ". Ligne {$error['ligne']}: {$error['matricule']} ({$error['nom']})");
                    $this->line("   → {$error['erreur']}");
                }
                if (count($summary['errors']) > 20) {
                    $this->line("   ... et " . (count($summary['errors']) - 20) . " autres erreurs");
                }
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'importation:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
