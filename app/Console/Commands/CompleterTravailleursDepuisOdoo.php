<?php

namespace App\Console\Commands;

use App\Categories;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\Pays;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Complète les fiches créées depuis le fichier de variables (ip = import-variables)
 * avec l'export Odoo « LISTE DU PERSONNEL » (référence employé = matricule ERH).
 *
 * Garde-fous : seules les fiches créées par l'import sont touchées, un champ déjà
 * renseigné n'est jamais écrasé, et une valeur Odoo sans libellé identique dans ERH
 * (ou avec un libellé dupliqué) n'est pas appliquée : elle est listée dans le rapport.
 */
class CompleterTravailleursDepuisOdoo extends Command
{
    protected $signature = 'travailleurs:completer-depuis-odoo
                            {fichier : Export Odoo (.xlsx) « LISTE DU PERSONNEL »}
                            {--ecrire : Écrire réellement (sans cette option : simulation)}';

    protected $description = 'Complète les fiches créées depuis les variables avec l\'export Odoo (simulation par défaut)';

    private const TYPE_CONTRAT = ['STAGE' => 1, 'CDD' => 2, 'CDI' => 3, 'JOURNALIER' => 4, 'CDDJ' => 4, 'STAGE ECOLE' => 5, 'STAGE DE QUALIFICATION' => 6];
    private const SITUATION = ['CELIBATAIRE' => 'Celibataire', 'MARIE(E)' => 'Marie', 'MARIE' => 'Marie', 'VEUF(VE)' => 'Veuf(ve)'];

    public function handle(): int
    {
        $fichier = $this->argument('fichier');
        if (!is_file($fichier)) {
            $this->error("Fichier introuvable : $fichier");

            return self::FAILURE;
        }
        $ecrire = (bool) $this->option('ecrire');
        $this->info($ecrire ? 'ÉCRITURE réelle en base' : 'SIMULATION : aucune écriture en base');

        $reader = IOFactory::createReaderForFile($fichier);
        $reader->setReadDataOnly(true);
        $lignes = $reader->load($fichier)->getSheet(0)->toArray(null, true, false, false);
        $entete = array_shift($lignes);
        $odoo = [];
        foreach ($lignes as $l) {
            $m = strtoupper(trim((string) ($l[0] ?? '')));
            if ($m !== '') {
                $odoo[$m] = array_combine($entete, array_pad($l, count($entete), null));
            }
        }

        $dep = $this->index(Departement::pluck('label', 'id'));
        $fon = $this->index(Fonction::pluck('label', 'id'));
        $cat = $this->index(Categories::pluck('label', 'id'));
        $pays = $this->index(Pays::pluck('label', 'id'));
        $equ = $this->index(Equipes::pluck('label', 'id'));   // Service Odoo = équipe ERH (99 % de concordance mesurée sur les fiches existantes)

        $fiches = Travailleur::where('ip', 'import-variables')->get();
        $stats = [];
        $nonApplique = [];
        $introuvables = [];
        $touchees = 0;

        foreach ($fiches as $t) {
            $o = $odoo[$t->matricule] ?? null;
            if (!$o) {
                $introuvables[] = $t->matricule;
                continue;
            }
            $maj = [];
            $vide = fn ($champ) => $t->$champ === null || $t->$champ === '' || $t->$champ === 'NULL';
            $mettre = function ($champ, $valeur) use (&$maj, $vide, $t) {
                if ($valeur !== null && $valeur !== '' && $vide($champ)) {
                    $maj[$champ] = $valeur;
                }
            };

            // Civilité : genre + état civil (une femme mariée ou veuve = Madame, célibataire = Mademoiselle)
            $genre = $this->norm($o['Genre']);
            $etat = $this->norm($o['État civil']);
            if ($genre === 'MASCULIN') {
                $mettre('civilite', 'Monsieur');
            } elseif ($genre === 'FEMININ' && $etat !== '') {
                $mettre('civilite', $etat === 'CELIBATAIRE' ? 'Mademoiselle' : 'Madame');
            }

            $sit = self::SITUATION[$etat] ?? null;
            if ($etat !== '' && !$sit) {
                $nonApplique['État civil'][$o['État civil']] = ($nonApplique['État civil'][$o['État civil']] ?? 0) + 1;
            }
            $mettre('situation_mat', $sit);

            if (is_numeric($o['Enfants à charge'])) {
                $mettre('nombre_enfant', (int) $o['Enfants à charge']);
            }
            $mettre('date_naissance', $this->date($o['Anniversaire'], '1930-01-01', '2010-12-31'));
            $mettre('date_debut_contrat', $this->date($o['Date de début du contrat'], '1990-01-01', '2026-12-31'));
            $cnps = preg_replace('/\s+/', '', (string) $o['Numéro CNPS']);
            $mettre('numero_securite', $cnps !== '' ? $cnps : null);
            $mobile = preg_replace('/\s+/', '', (string) $o['Mobile']);
            $mettre('telephone', $mobile !== '' ? $mobile : null);

            $tc = self::TYPE_CONTRAT[$this->norm($o['Type de contrat'])] ?? null;
            if ($this->norm($o['Type de contrat']) !== '' && !$tc) {
                $nonApplique['Type de contrat'][$o['Type de contrat']] = ($nonApplique['Type de contrat'][$o['Type de contrat']] ?? 0) + 1;
            }
            $mettre('idtype_contrat', $tc);

            foreach ([['Département', 'departementid', $dep], ['Service', 'equipeid', $equ], ['Poste', 'fonction_entrepriseid', $fon], ["Catégorie d'employé", 'categorieid', $cat], ['Nationalité (pays)', 'nationaliteid', $pays]] as [$col, $champ, $index]) {
                $val = $this->norm($o[$col]);
                if ($val === '') {
                    continue;
                }
                if (isset($index[$val])) {
                    $mettre($champ, $index[$val]);
                } else {
                    $nonApplique[$col][trim((string) $o[$col])] = ($nonApplique[$col][trim((string) $o[$col])] ?? 0) + 1;
                }
            }

            foreach ($maj as $champ => $v) {
                $stats[$champ] = ($stats[$champ] ?? 0) + 1;
            }
            if ($maj) {
                $touchees++;
                if ($ecrire) {
                    $t->forceFill($maj)->save();
                }
            }
        }

        $this->info(sprintf('%d fiche(s) créées par l\'import, %d retrouvées dans Odoo, %d à mettre à jour.', $fiches->count(), $fiches->count() - count($introuvables), $touchees));
        ksort($stats);
        foreach ($stats as $champ => $n) {
            $this->line(sprintf('   %-22s %3d fiche(s)', $champ, $n));
        }
        if ($nonApplique) {
            $this->warn('Valeurs Odoo NON appliquées (pas de libellé identique et unique dans ERH) :');
            foreach ($nonApplique as $col => $vals) {
                arsort($vals);
                $this->line("   $col : " . implode(' ; ', array_map(fn ($v, $n) => "$v ($n)", array_keys($vals), $vals)));
            }
        }
        $this->line('Absentes d\'Odoo (' . count($introuvables) . ') : ' . implode(', ', $introuvables));

        return self::SUCCESS;
    }

    private function norm($s): string
    {
        $s = mb_strtoupper(trim((string) $s), 'UTF-8');
        $s = preg_replace('/\s+/', ' ', $s);

        return strtr($s, ['É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'À' => 'A', 'Â' => 'A', 'Î' => 'I', 'Ï' => 'I', 'Ô' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ç' => 'C', '’' => "'"]);
    }

    /** Index libellé normalisé => id, sans les libellés dupliqués (impossible de savoir lequel est le bon). */
    private function index($labels): array
    {
        $par = [];
        foreach ($labels as $id => $label) {
            $par[$this->norm($label)][] = $id;
        }

        return array_map(fn ($ids) => $ids[0], array_filter($par, fn ($ids) => count($ids) === 1));
    }

    private function date($v, string $min, string $max): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }
        try {
            $d = is_numeric($v) ? Carbon::instance(Date::excelToDateTimeObject($v)) : Carbon::parse((string) $v);
        } catch (\Throwable $e) {
            return null;
        }
        $s = $d->format('Y-m-d');

        return ($s >= $min && $s <= $max) ? $s : null;
    }
}
