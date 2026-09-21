<?php

namespace App\Console\Commands;

use App\Services\ImportVariablesPaie;
use App\Travailleur;
use App\VariablePaie;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Crée une fiche minimale pour chaque matricule présent dans le fichier Excel des
 * variables (donc payé) mais absent de la table des travailleurs.
 *
 * Seules les informations du fichier sont renseignées (matricule, nom, prénoms) ;
 * le reste est laissé vide pour que les RH le complètent. Aucune date ni motif de
 * cessation n'est inventé.
 */
class CreerTravailleursDepuisVariables extends Command
{
    protected $signature = 'travailleurs:creer-depuis-variables
                            {fichier : Fichier Excel des variables}
                            {--ecrire : Créer réellement les fiches (sans cette option : simulation)}';

    protected $description = 'Crée les fiches travailleurs manquantes à partir du fichier Excel des variables (simulation par défaut)';

    public function handle(ImportVariablesPaie $service): int
    {
        $fichier = $this->argument('fichier');
        if (!is_file($fichier)) {
            $this->error("Fichier introuvable : $fichier");

            return self::FAILURE;
        }
        $ecrire = (bool) $this->option('ecrire');
        $this->info($ecrire ? 'CRÉATION RÉELLE en base' : 'SIMULATION : aucune écriture en base');

        $connus = Travailleur::pluck('id', 'matricule')->all();
        $fiches = [];   // matricule => [nom, prenom, mois[]]
        $dernierMois = 0;

        foreach ($service->feuilles($fichier) as $nomFeuille) {
            $periode = $service->periodeDepuisNom($nomFeuille);
            if (!$periode) {
                continue;
            }
            $ordre = $periode[0] * 100 + $periode[1];
            $dernierMois = max($dernierMois, $ordre);

            $reader = IOFactory::createReaderForFile($fichier);
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly([$nomFeuille]);
            $classeur = $reader->load($fichier);
            $ws = $classeur->getActiveSheet();
            foreach ($ws->rangeToArray('A3:D' . $ws->getHighestDataRow(), null, true, false, false) as $ligne) {
                $matricule = strtoupper(trim((string) $ligne[0]));
                if (!preg_match('/^[A-Z]\d{3,}$/', $matricule) || isset($connus[$matricule])) {
                    continue;
                }
                $nom = strtoupper(preg_replace('/\s+/', ' ', trim((string) $ligne[2])));
                $prenom = strtoupper(preg_replace('/\s+/', ' ', trim((string) $ligne[3])));
                $f = &$fiches[$matricule];
                $f['mois'][] = $ordre;
                // On garde le nom le plus complet (le prénom est parfois complété en cours d'année)
                if (!isset($f['prenom']) || mb_strlen($prenom) >= mb_strlen($f['prenom'])) {
                    $f['nom'] = $nom;
                    $f['prenom'] = $prenom;
                }
                unset($f);
            }
            $classeur->disconnectWorksheets();
            unset($classeur, $ws);
        }
        ksort($fiches);

        $creees = 0;
        $actifs = 0;
        $partis = 0;
        $sansNom = [];
        $rapport = [['matricule', 'nom', 'prenoms', 'statut_cree', 'premier_mois', 'dernier_mois']];

        foreach ($fiches as $matricule => $f) {
            if ($f['nom'] === '') {
                $sansNom[] = $matricule;
                continue;
            }
            $encorePresent = in_array($dernierMois, $f['mois'], true);
            $etape = $encorePresent ? 2 : 3;
            $encorePresent ? $actifs++ : $partis++;

            $prenom = mb_substr($f['prenom'], 0, 19);
            $suite = mb_substr($f['prenom'], 19);
            $fmt = fn ($o) => Carbon::create(intdiv($o, 100), $o % 100, 1)->format('m/Y');
            $rapport[] = [$matricule, $f['nom'], $f['prenom'], $encorePresent ? 'enregistré (étape 2)' : 'cessation (étape 3)', $fmt(min($f['mois'])), $fmt(max($f['mois']))];

            if ($ecrire) {
                $t = new Travailleur();
                $t->matricule = $matricule;
                $t->nom = $f['nom'];
                $t->prenom = $prenom;
                $t->prenom_suite = $suite !== '' ? $suite : null;
                $t->etapeid = $etape;
                $t->statutid = 2;
                $t->type_employer = 2;
                $t->inscrit_le = Carbon::now()->toDateString();
                $t->ip = 'import-variables';
                $t->save();
                VariablePaie::where('matricule', $matricule)->whereNull('travailleurid')->update(['travailleurid' => $t->id]);
            }
            $creees++;
        }

        if ($sansNom) {
            $this->warn(count($sansNom) . ' matricule(s) ignoré(s) car sans nom dans le fichier : ' . implode(', ', $sansNom));
        }
        $this->info(sprintf('%d fiche(s) %s : %d enregistrée(s) (présentes en dernière feuille), %d en cessation.', $creees, $ecrire ? 'créée(s)' : 'à créer', $actifs, $partis));

        $chemin = storage_path('app/travailleurs_crees_depuis_variables_' . date('Ymd_His') . '.csv');
        $h = fopen($chemin, 'w');
        fwrite($h, "\xEF\xBB\xBF");
        foreach ($rapport as $l) {
            fputcsv($h, $l, ';');
        }
        fclose($h);
        $this->line("Liste à compléter par les RH : $chemin");

        return self::SUCCESS;
    }
}
