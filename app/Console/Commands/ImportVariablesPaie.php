<?php

namespace App\Console\Commands;

use App\Services\ImportVariablesPaie as Service;
use App\Support\CatalogueVariablesPaie as Cat;
use Illuminate\Console\Command;

class ImportVariablesPaie extends Command
{
    protected $signature = 'variables:import
                            {fichier : Chemin du fichier Excel des variables}
                            {--feuille=* : Ne traiter que ces feuilles (ex. --feuille="SEPT" --feuille="AOUT")}
                            {--ecrire : Écrire en base (sans cette option : simulation, rien n\'est modifié)}';

    protected $description = 'Importe les variables de paie mensuelles depuis le fichier Excel (simulation par défaut)';

    public function handle(Service $service): int
    {
        $fichier = $this->argument('fichier');
        if (!is_file($fichier)) {
            $this->error("Fichier introuvable : $fichier");

            return self::FAILURE;
        }

        $ecrire = (bool) $this->option('ecrire');
        $this->info($ecrire ? 'IMPORT RÉEL en base' : 'SIMULATION : aucune écriture en base');

        $rapport = $service->importer($fichier, !$ecrire, $this->option('feuille') ?: null);

        $totalCellules = 0;
        foreach ($rapport as $nom => $r) {
            if (isset($r['ignoree'])) {
                $this->warn("$nom : ignorée ({$r['ignoree']})");
                continue;
            }
            $totalCellules += $r['cellules'];
            $this->line(sprintf(
                '<info>%s</info> (%02d/%d) : %d travailleurs, %d valeurs, %d matricules absents de la base, %d anomalies%s',
                $nom, $r['mois'], $r['annee'], $r['travailleurs'], $r['cellules'],
                count($r['matricules_inconnus']), count($r['anomalies']),
                isset($r['ignorees_car_manuelles']) ? ", {$r['ignorees_car_manuelles']} ignorées (saisie manuelle)" : ''
            ));
            foreach ($r['anomalies'] as $a) {
                $this->warn("   $a");
            }
            if ($r['matricules_inconnus']) {
                $this->line('   absents : ' . implode(', ', array_slice($r['matricules_inconnus'], 0, 12)) . (count($r['matricules_inconnus']) > 12 ? ' …' : ''));
            }
            if ($this->getOutput()->isVerbose()) {
                foreach ($r['par_code'] as $code => $t) {
                    $this->line(sprintf('   %-24s n=%-4d somme=%s', Cat::libelle($code), $t['n'], $t['somme']));
                }
            }
        }
        $this->info("Total : $totalCellules valeurs" . ($ecrire ? ' écrites.' : ' (simulation).'));

        return self::SUCCESS;
    }
}
