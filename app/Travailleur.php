<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Travailleur extends Model
{
    protected $table = 'e_travailleur';

    /**
     * Motifs de fin de contrat non ambigus : un vrai départ, jamais un texte générique
     * ("FIN CONTRAT"/"fin de contrat" hérité, posé par défaut sur tout contrat journalier
     * à durée déterminée, qui ne prouve rien à lui seul).
     */
    public const MOTIFS_CESSATION_NON_AMBIGUS = ['ABANDON', 'LICENCIEMENT', 'DEMISSION', 'ACCIDENT DE TRAVAIL', 'MALADIE PROLONGÉE', 'CONGE DE MATERNITE'];

    /**
     * Travailleurs avec une vraie cessation confirmée, par l'une ou l'autre de deux sources :
     * (1) la DERNIÈRE action e_action_cdc (par date_choisit) est une cessation (actionid=3) non
     *     suivie d'une embauche/reconduction plus récente (actionid 1 ou 6) ;
     * (2) un motif de fin de contrat non ambigu est enregistré sur le travailleur.
     * La source (1) seule est incomplète : RecruController::updatedeclaration ne crée une ligne
     * e_action_cdc que pour la toute première cessation d'un travailleur — toute cessation
     * suivante (après une reconduction) échoue silencieusement à créer une nouvelle trace d'audit
     * (bug corrigé par ailleurs), d'où la source (2) en complément.
     * Une vraie cessation confirmée l'emporte toujours, quelle que soit la date d'embauche —
     * les règles 1/2 ci-dessous ne rattrapent que les etapeid=3 SANS cessation confirmée
     * (donnée incohérente), jamais une cessation réelle non réversée.
     */
    public static function cessesConfirmes()
    {
        $parActionCdc = DB::table('e_action_cdc as a')
            ->where('a.actionid', 3)
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('e_action_cdc as a2')
                  ->whereColumn('a2.travailleurid', 'a.travailleurid')
                  ->whereIn('a2.actionid', [1, 6])
                  ->whereColumn('a2.date_choisit', '>', 'a.date_choisit');
            })
            ->pluck('a.travailleurid');

        $parMotif = self::whereIn(DB::raw('UPPER(motif_fin_contrat)'), self::MOTIFS_CESSATION_NON_AMBIGUS)
            ->pluck('id');

        return $parActionCdc->merge($parMotif)->unique()->values();
    }

    /**
     * Actif = etapeid != 4 (certificat de travail = état final), ET
     * (etapeid != 3 OU (pas de cessation confirmée ET (embauché après le 31-12-2024 OU
     * fin de contrat après le 01-01-2025))).
     * Voir /home/daniel/.claude/plans/ethereal-jingling-coral.md.
     */
    public function scopeActif($query)
    {
        $cessesConfirmes = self::cessesConfirmes();

        return $query->where('etapeid', '!=', 4)
            ->where(function ($q) use ($cessesConfirmes) {
                $q->where('etapeid', '!=', 3)
                  ->orWhere(function ($q2) use ($cessesConfirmes) {
                      $q2->whereNotIn('id', $cessesConfirmes)
                         ->where(function ($q3) {
                             $q3->where('date_debut_contrat', '>', '2024-12-31')
                                ->orWhere('date_fin_contrat', '>=', '2025-01-01');
                         });
                  });
            });
    }

    /**
     * Négation exacte de scopeActif() restreinte à etapeid == 3 : les cessations
     * réellement confirmées (pas d'exception règle 1/règle 2).
     */
    public function scopeCesseConfirme($query)
    {
        $cessesConfirmes = self::cessesConfirmes();

        return $query->where('etapeid', 3)
            ->where(function ($q) use ($cessesConfirmes) {
                $q->whereIn('id', $cessesConfirmes)
                  ->orWhere(function ($q2) {
                      $q2->where('date_debut_contrat', '<=', '2024-12-31')
                         ->where('date_fin_contrat', '<', '2025-01-01');
                  });
            });
    }

    /**
     * URL de téléchargement direct du contrat PDF
     *
     * @return string
     */
    public function getContratUrlAttribute(): string
    {
        if ($this->idtype_contrat == 2) {
            return route('telechargerContratCDD', ['id' => $this->id, 'download' => 'pdf']);
        } elseif ($this->idtype_contrat == 3) {
            return route('telechargerContratCDI', ['id' => $this->id, 'download' => 'pdf']);
        } else {
            return route('telechargerContratJournalier', ['id' => $this->id, 'download' => 'pdf']);
        }
    }

    /**
     * Libellé lisible du type de contrat
     *
     * @return string
     */
    public function getContratLibelleAttribute(): string
    {
        if ($this->idtype_contrat == 2) {
            return 'Contrat CDD';
        } elseif ($this->idtype_contrat == 3) {
            return 'Contrat CDI';
        } else {
            return 'Contrat Journalier';
        }
    }

    /**
     * Prénoms complets. Sage coupe le prénom à 19 caractères : dans ce cas
     * prenom_suite prolonge le mot coupé (aucune espace). Sinon (données
     * historiques) prenom_suite est un prénom distinct, séparé par une espace.
     *
     * @return string
     */
    public function getPrenomsCompletsAttribute(): string
    {
        $prenom = (string) $this->prenom;
        $suite  = trim((string) $this->prenom_suite);

        if ($suite === '' || strtoupper($suite) === 'NULL') {
            return $prenom;
        }

        return $prenom . (mb_strlen($prenom) >= 19 ? '' : ' ') . $suite;
    }

    /** Largeur (en chiffres) du numéro pour chaque série de matricule : E01921, J0008081, S00001. */
    private const LARGEUR_MATRICULE = ['E' => 5, 'J' => 7, 'S' => 5];

    /**
     * Prochain matricule libre d'une série (E = CDD/CDI, J = journaliers, S = stagiaires).
     * On repart du plus grand numéro existant de la série.
     */
    public static function prochainMatricule(string $prefixe): string
    {
        $prefixe = strtoupper($prefixe);
        $max = 0;
        foreach (self::where('matricule', 'like', $prefixe . '%')->pluck('matricule') as $m) {
            if (preg_match('/^' . $prefixe . '(\d+)$/', $m, $r)) {
                $max = max($max, (int) $r[1]);
            }
        }

        return $prefixe . str_pad((string) ($max + 1), self::LARGEUR_MATRICULE[$prefixe] ?? 5, '0', STR_PAD_LEFT);
    }
}
