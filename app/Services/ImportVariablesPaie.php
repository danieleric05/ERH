<?php

namespace App\Services;

use App\Support\CatalogueVariablesPaie as Cat;
use App\Travailleur;
use App\VariablePaie;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Import des feuilles mensuelles du fichier Excel « VARIABLES » vers e_variable_paie.
 *
 * Idempotent : relancer l'import met à jour les valeurs déjà importées
 * (clé matricule + année + mois + code + semaine) sans créer de doublons, et ne
 * touche jamais aux lignes saisies à la main (source = manuel).
 */
class ImportVariablesPaie
{
    private const MOIS = [
        'JANVIER' => 1, 'FEVRIER' => 2, 'MARS' => 3, 'AVRIL' => 4, 'MAI' => 5, 'JUIN' => 6,
        'JUILLET' => 7, 'AOUT' => 8, 'SEPT' => 9, 'SEPTEMBRE' => 9, 'OCTOBRE' => 10,
        'OCT' => 10, 'NOVEMBRE' => 11, 'NOV' => 11, 'DECEMBRE' => 12, 'DEC' => 12,
    ];

    /** Noms des feuilles du fichier. */
    public function feuilles(string $fichier): array
    {
        $reader = IOFactory::createReaderForFile($fichier);
        $reader->setReadDataOnly(true);

        return array_map(fn ($s) => $s['worksheetName'], $reader->listWorksheetInfo($fichier));
    }

    /** Déduit [annee, mois] d'un nom de feuille comme « SEPT 2026 » ou « AOUT  2026 ». */
    public function periodeDepuisNom(string $nom): ?array
    {
        $n = strtoupper(preg_replace('/\s+/', ' ', trim($nom)));
        $n = strtr($n, ['É' => 'E', 'È' => 'E', 'Û' => 'U']);
        if (!preg_match('/^([A-Z]+)\s+(\d{4})$/', $n, $m) || !isset(self::MOIS[$m[1]])) {
            return null;
        }

        return [(int) $m[2], self::MOIS[$m[1]]];
    }

    /**
     * @param  string[]|null  $feuillesVoulues  noms (ou débuts de nom) à importer, null = toutes
     * @return array rapport par feuille
     */
    public function importer(string $fichier, bool $simulation = true, ?array $feuillesVoulues = null, ?int $userid = null): array
    {
        $idParMatricule = Travailleur::pluck('id', 'matricule')->all();
        $rapport = [];

        foreach ($this->feuilles($fichier) as $nomFeuille) {
            if ($feuillesVoulues && !$this->voulue($nomFeuille, $feuillesVoulues)) {
                continue;
            }

            $periode = $this->periodeDepuisNom($nomFeuille);
            if (!$periode) {
                $rapport[trim($nomFeuille)] = ['ignoree' => 'nom de feuille non reconnu comme un mois'];
                continue;
            }

            [$annee, $mois] = $periode;
            $rapport[trim($nomFeuille)] = $this->importerFeuille($fichier, $nomFeuille, $annee, $mois, $idParMatricule, $simulation, $userid);
        }

        return $rapport;
    }

    private function voulue(string $nom, array $voulues): bool
    {
        foreach ($voulues as $v) {
            if (stripos(trim($nom), trim($v)) === 0) {
                return true;
            }
        }

        return false;
    }

    private function importerFeuille(string $fichier, string $nomFeuille, int $annee, int $mois, array $idParMatricule, bool $simulation, ?int $userid): array
    {
        $reader = IOFactory::createReaderForFile($fichier);
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly([$nomFeuille]);
        $classeur = $reader->load($fichier);
        $ws = $classeur->getActiveSheet();
        $lignes = $ws->rangeToArray('A1:' . $this->lettre(Cat::derniereColonne() + Cat::NB_SEMAINES) . $ws->getHighestDataRow(), null, true, false, false);
        $classeur->disconnectWorksheets();
        unset($classeur, $ws);

        $periodes = $this->libellesPeriodes($lignes[1] ?? []);
        $rapport = [
            'annee' => $annee, 'mois' => $mois, 'travailleurs' => 0, 'cellules' => 0,
            'par_code' => [], 'matricules_inconnus' => [], 'anomalies' => [],
        ];
        $aInserer = [];
        $maintenant = now();

        foreach (array_slice($lignes, 2) as $i => $ligne) {
            $matricule = strtoupper(trim((string) ($ligne[0] ?? '')));
            if (!preg_match('/^[A-Z]\d{3,}$/', $matricule)) {
                continue;
            }
            $rapport['travailleurs']++;
            $travailleurId = $idParMatricule[$matricule] ?? null;
            if (!$travailleurId) {
                $rapport['matricules_inconnus'][$matricule] = true;
            }

            foreach (Cat::CODES as $code => $def) {
                $nb = !empty($def['hebdo']) ? Cat::NB_SEMAINES : 1;
                for ($s = 0; $s < $nb; $s++) {
                    $brut = $ligne[$def['colonne'] + $s] ?? null;
                    if ($brut === null || (is_string($brut) && trim($brut) === '')) {
                        continue;
                    }
                    if (!is_numeric($brut)) {
                        $rapport['anomalies'][] = "L" . ($i + 3) . " $matricule $code : valeur non numérique « " . trim((string) $brut) . " »";
                        continue;
                    }
                    $valeur = (float) $brut;
                    if ($valeur == 0.0) {
                        continue;
                    }
                    $semaine = !empty($def['hebdo']) ? $s + 1 : 0;
                    $aInserer[] = [
                        'travailleurid' => $travailleurId,
                        'matricule' => $matricule,
                        'annee' => $annee,
                        'mois' => $mois,
                        'code' => $code,
                        'semaine' => $semaine,
                        'periode_libelle' => $semaine ? ($periodes[$def['colonne'] + $s] ?? null) : null,
                        'valeur' => $valeur,
                        'source' => 'import',
                        'userid' => $userid,
                        'created_at' => $maintenant,
                        'updated_at' => $maintenant,
                    ];
                    $rapport['par_code'][$code]['n'] = ($rapport['par_code'][$code]['n'] ?? 0) + 1;
                    $rapport['par_code'][$code]['somme'] = ($rapport['par_code'][$code]['somme'] ?? 0) + $valeur;
                }
            }
        }

        $rapport['cellules'] = count($aInserer);
        $rapport['matricules_inconnus'] = array_keys($rapport['matricules_inconnus']);

        if (!$simulation && $aInserer) {
            // Ne jamais écraser une saisie manuelle du même mois.
            $manuelles = VariablePaie::where('annee', $annee)->where('mois', $mois)->where('source', 'manuel')
                ->get(['matricule', 'code', 'semaine'])
                ->map(fn ($v) => "$v->matricule|$v->code|$v->semaine")->flip();
            $aInserer = array_values(array_filter($aInserer, fn ($r) => !isset($manuelles["{$r['matricule']}|{$r['code']}|{$r['semaine']}"])));
            $rapport['ignorees_car_manuelles'] = $rapport['cellules'] - count($aInserer);

            foreach (array_chunk($aInserer, 500) as $lot) {
                VariablePaie::upsert(
                    $lot,
                    ['matricule', 'annee', 'mois', 'code', 'semaine'],
                    ['travailleurid', 'periode_libelle', 'valeur', 'userid', 'updated_at']
                );
            }
        }

        return $rapport;
    }

    /** Libellés de la ligne 2 (dates Excel converties en jj/mm/aaaa). */
    private function libellesPeriodes(array $ligne2): array
    {
        $out = [];
        foreach ($ligne2 as $c => $v) {
            if ($v === null || trim((string) $v) === '') {
                continue;
            }
            if (is_numeric($v) && $v > 40000) {
                $out[$c] = Date::excelToDateTimeObject($v)->format('d/m/Y');
            } else {
                $out[$c] = mb_substr(trim(preg_replace('/\s+/', ' ', (string) $v)), 0, 60);
            }
        }

        return $out;
    }

    private function lettre(int $index): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
    }
}
