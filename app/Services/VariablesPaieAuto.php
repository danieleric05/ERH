<?php

namespace App\Services;

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
}
