<?php

namespace App\Console\Commands;

use App\Services\ImportRepertoireContrats;
use Illuminate\Console\Command;

class ImporterRepertoireContrats extends Command
{
    protected $signature = 'travailleurs:importer-repertoire
                            {fichier : Répertoire CDD (.xlsx) : stagiaires, CDD, CDI, journaliers}
                            {--ecrire : Écrire réellement en base (sans cette option : simulation)}
                            {--utilisateur= : Identifiant de l\'utilisateur ERH enregistré comme auteur (obligatoire avec --ecrire)}';

    protected $description = 'Importe travailleurs et contrats depuis le Répertoire CDD (simulation par défaut)';

    public function handle(ImportRepertoireContrats $service): int
    {
        $fichier = $this->argument('fichier');
        if (!is_file($fichier)) {
            $this->error("Fichier introuvable : $fichier");

            return self::FAILURE;
        }
        $ecrire = (bool) $this->option('ecrire');
        $userid = $this->option('utilisateur') ? (int) $this->option('utilisateur') : null;
        if ($ecrire && !$userid) {
            $this->error('Avec --ecrire, indiquez --utilisateur=ID (auteur des actions dans l\'historique des contrats).');

            return self::FAILURE;
        }
        $this->info($ecrire ? 'IMPORT RÉEL en base' : 'SIMULATION : aucune écriture en base');

        @ini_set('memory_limit', '1G');
        try {
            $rapport = $service->importer($fichier, !$ecrire, $userid);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        foreach ($rapport['lignes'] as $l) {
            $ligne = sprintf('L%-4d %-11s %-10s %s', $l['ligne'], $l['statut'], $l['matricule'] ?? '-', mb_substr($l['nom'], 0, 34));
            $l['statut'] === 'rejeté' ? $this->error($ligne . '  → ' . $l['erreur']) : $this->line($ligne);
            foreach ($l['avertissements'] as $a) {
                $this->warn("        ⚠ $a");
            }
            if ($this->getOutput()->isVerbose()) {
                foreach ($l['changements'] as $c) {
                    $this->line("        · $c");
                }
            }
        }
        $t = $rapport['total'];
        $this->info(sprintf('Bilan : %d créé(s), %d mis à jour, %d reconduit(s), %d historisé(s), %d inchangé(s), %d rejeté(s).', $t['crees'], $t['mis_a_jour'], $t['reconduits'], $t['historises'], $t['inchanges'], $t['rejetes']));

        $csv = $this->ecrireCsv($rapport);
        $this->line("Rapport détaillé : $csv");

        return self::SUCCESS;
    }

    private function ecrireCsv(array $rapport): string
    {
        $dir = storage_path('app/imports_contrats');
        @mkdir($dir, 0775, true);
        $chemin = $dir . '/rapport_' . date('Ymd_His') . '.csv';
        $h = fopen($chemin, 'w');
        fwrite($h, "\xEF\xBB\xBF");
        fputcsv($h, ['ligne', 'statut', 'matricule', 'nom', 'changements', 'avertissements', 'erreur'], ';');
        foreach ($rapport['lignes'] as $l) {
            fputcsv($h, [$l['ligne'], $l['statut'], $l['matricule'], $l['nom'], implode(' | ', $l['changements']), implode(' | ', $l['avertissements']), $l['erreur']], ';');
        }
        fclose($h);

        return $chemin;
    }
}
