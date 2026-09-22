<?php

namespace App\Services;

use App\Support\CatalogueVariablesPaie as Cat;
use App\VariablePaie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Variables de paie calculées depuis les autres modules (santé, autorisations, sanctions).
 */
class VariablesPaieAuto
{
    /** Motifs d'autorisation comptés comme « absence justifiée » (1 = Maladie est déjà dans l'arrêt maladie, 4/5 = congés). */
    public const MOTIFS_ABSENCE_JUSTIFIEE = [2, 3];

    /** Jours d'arrêt maladie, d'absence justifiée et de sanction du mois, calculés depuis les autres modules. */
    public static function pourMois(int $annee, int $mois): array
    {
        $debut = Carbon::create($annee, $mois, 1)->startOfDay();
        $fin = $debut->copy()->endOfMonth()->startOfDay();
        $out = [];

        $jours = function ($d, $f) use ($debut, $fin) {
            $d = Carbon::parse($d)->startOfDay();
            $f = Carbon::parse($f)->startOfDay();
            $a = $d->gt($debut) ? $d : $debut;
            $b = $f->lt($fin) ? $f : $fin;

            return $b->gte($a) ? $a->diffInDays($b) + 1 : 0;
        };

        // Arrêt maladie : consultations avec arrêt de travail chevauchant le mois
        $maladies = DB::table('e_consultation')->where('statutid', 1)->where('arret_travail', 1)
            ->whereNotNull('debut_arret')->whereNotNull('fin_arret')
            ->where('debut_arret', '<=', $fin->toDateString())->where('fin_arret', '>=', $debut->toDateString())
            ->get(['travailleur_mat', 'debut_arret', 'fin_arret']);
        foreach ($maladies as $m) {
            if ($m->travailleur_mat && ($j = $jours($m->debut_arret, $m->fin_arret))) {
                $out[$m->travailleur_mat]['ARRET_MALADIE'] = ($out[$m->travailleur_mat]['ARRET_MALADIE'] ?? 0) + $j;
            }
        }

        // Absence justifiée : autorisations de type convenance personnelle / permission exceptionnelle
        $autorisations = DB::table('e_autorisation as a')->join('e_travailleur as t', 't.id', '=', 'a.demandeurid')
            ->where('a.statutid', 1)->whereIn('a.motif_absence', self::MOTIFS_ABSENCE_JUSTIFIEE)
            ->whereNotNull('a.debut')->whereNotNull('a.fin')
            ->where('a.debut', '<=', $fin->toDateString())->where('a.fin', '>=', $debut->toDateString())
            ->get(['t.matricule', 'a.debut', 'a.fin']);
        foreach ($autorisations as $a) {
            if ($j = $jours($a->debut, $a->fin)) {
                $out[$a->matricule]['ABSENCE_JUSTIFIEE'] = ($out[$a->matricule]['ABSENCE_JUSTIFIEE'] ?? 0) + $j;
            }
        }

        // Sanction disciplinaire : nombre de jours de mise à pied dont le début tombe dans le mois
        $sanctions = DB::table('e_sanction')->where('statutid', 1)->where('nombre_jour', '>', 0)
            ->whereBetween('debut', [$debut->toDateString(), $fin->toDateString()])
            ->get(['employeid', 'nombre_jour']);
        foreach ($sanctions as $s) {
            $liste = @unserialize($s->employeid);
            foreach (is_array($liste) ? $liste : [] as $matricule) {
                $out[$matricule]['SANCTION'] = ($out[$matricule]['SANCTION'] ?? 0) + $s->nombre_jour;
            }
        }

        return $out;
    }

    /**
     * Valeurs d'un mois : enregistrées (import ou saisie) sinon calculées.
     *
     * @return array{0: array<string, array<string, float>>, 1: array<string, array<string, string>>}
     *         valeurs[matricule][code] et sources[matricule][code] ('enregistre' | 'auto')
     */
    public static function valeursDuMois(int $annee, int $mois): array
    {
        $valeurs = [];
        $sources = [];

        foreach (self::pourMois($annee, $mois) as $matricule => $codes) {
            foreach ($codes as $code => $v) {
                $valeurs[$matricule][$code] = $v;
                $sources[$matricule][$code] = 'auto';
            }
        }

        $sommes = [];
        foreach (VariablePaie::where('annee', $annee)->where('mois', $mois)->whereIn('code', Cat::visibles())->get(['matricule', 'code', 'valeur']) as $e) {
            $sommes[$e->matricule][$e->code] = ($sommes[$e->matricule][$e->code] ?? 0) + $e->valeur;
        }
        foreach ($sommes as $matricule => $codes) {
            foreach ($codes as $code => $v) {
                $valeurs[$matricule][$code] = $v;          // l'enregistré l'emporte sur le calcul
                $sources[$matricule][$code] = 'enregistre';
            }
        }

        return [$valeurs, $sources];
    }

    /** Dernière période (annee, mois) qui contient des valeurs enregistrées pour ces codes, sinon le mois courant. */
    public static function periodeParDefaut(array $codes): array
    {
        $d = VariablePaie::whereIn('code', $codes)->orderByDesc('annee')->orderByDesc('mois')->first(['annee', 'mois']);

        return $d ? [(int) $d->annee, (int) $d->mois] : [(int) date('Y'), (int) date('n')];
    }

    /**
     * Lignes d'un groupe de variables (presence | hs | autres) pour un mois, prêtes pour une liste.
     * Chaque ligne : matricule, nom, code, libelle, unite, total, source ('auto' | 'enregistre'),
     * semaines [n => valeur] (variables hebdomadaires enregistrées).
     */
    public static function lignesDuGroupe(string $groupe, int $annee, int $mois): array
    {
        $codes = Cat::codesDuGroupe($groupe);
        [$valeurs, $sources] = self::valeursDuMois($annee, $mois);

        $semaines = [];
        foreach (VariablePaie::where('annee', $annee)->where('mois', $mois)->whereIn('code', $codes)->where('semaine', '>', 0)->get(['matricule', 'code', 'semaine', 'valeur']) as $l) {
            $semaines[$l->matricule][$l->code][$l->semaine] = $l->valeur;
        }

        $travailleurs = \App\Travailleur::whereIn('matricule', array_keys($valeurs))->get()->keyBy('matricule');
        $lignes = [];
        foreach ($valeurs as $matricule => $parCode) {
            foreach ($codes as $code) {
                if (!isset($parCode[$code])) {
                    continue;
                }
                $tr = $travailleurs->get($matricule);
                $lignes[] = (object) [
                    'matricule' => $matricule,
                    'nom' => $tr ? trim($tr->nom . ' ' . $tr->prenoms_complets) : '(matricule inconnu)',
                    'connu' => (bool) $tr,
                    'code' => $code,
                    'libelle' => Cat::libelle($code),
                    'unite' => Cat::CODES[$code]['unite'],
                    'total' => $parCode[$code],
                    'source' => $sources[$matricule][$code],
                    'semaines' => $semaines[$matricule][$code] ?? [],
                ];
            }
        }
        usort($lignes, fn ($a, $b) => [$a->matricule, $a->libelle] <=> [$b->matricule, $b->libelle]);

        return $lignes;
    }
}
