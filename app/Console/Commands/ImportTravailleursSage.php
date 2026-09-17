<?php

namespace App\Console\Commands;

use App\Imports\TravailleurSageImport;
use Illuminate\Console\Command;

class ImportTravailleursSage extends Command
{
    protected $signature = 'travailleurs:import-sage
        {file : Chemin du fichier CSV Sage}
        {--type= : journalier ou embauche (déduit du nom de fichier si omis)}
        {--dry-run : Simuler sans écrire en base}';

    protected $description = "Importe/met à jour les travailleurs (journaliers ou embauchés) depuis un export CSV Sage";

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $dryRun = (bool) $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("❌ Fichier non trouvé: {$filePath}");
            return 1;
        }

        $type = $this->option('type');
        if (!$type) {
            $type = stripos(basename($filePath), 'journalier') !== false ? 'journalier' : 'embauche';
        }
        if (!in_array($type, ['journalier', 'embauche'], true)) {
            $this->error("❌ --type doit être 'journalier' ou 'embauche'");
            return 1;
        }

        $this->info("📂 Fichier: {$filePath}");
        $this->info("🏷️  Type: {$type}");
        $this->line("   Taille: " . number_format(filesize($filePath) / 1024, 2) . " KB");
        if ($dryRun) {
            $this->warn("MODE SIMULATION — aucune écriture en base");
        }

        $import = new TravailleurSageImport();

        $start = microtime(true);
        $import->importFromFile($filePath, $type, $dryRun);
        $duration = round(microtime(true) - $start, 2);

        $summary = $import->getSummary();

        $this->newLine();
        $this->info("✅ Terminé en {$duration}s");
        $this->info('📊 RÉSUMÉ');
        $this->line('  🆕 Créés      : ' . $summary['created']);
        $this->line('  ♻️  Mis à jour : ' . $summary['updated']);
        $this->line('  ❌ Erreurs    : ' . count($summary['errors']));
        $this->line('  ━━━━━━━━━━━━━━━━━━━');
        $this->line('  📈 Total      : ' . $summary['total']);

        if (!empty($summary['errors'])) {
            $this->newLine();
            $this->warn('⚠️  ERREURS (premiers 20)');
            foreach (array_slice($summary['errors'], 0, 20) as $i => $error) {
                $this->line(($i + 1) . ". Ligne {$error['ligne']}: {$error['matricule']} ({$error['nom']}) → {$error['erreur']}");
            }
        }

        if (!empty($summary['unresolved'])) {
            $this->newLine();
            $this->warn('⚠️  VALEURS NON RÉSOLUES (champ non modifié pour les fiches existantes, laissé vide pour les nouvelles)');
            foreach ($summary['unresolved'] as $field => $values) {
                $this->line("  {$field}:");
                arsort($values);
                foreach ($values as $value => $count) {
                    $this->line("    - \"{$value}\" ({$count} ligne(s))");
                }
            }
        }

        return 0;
    }
}
