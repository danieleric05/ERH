<?php

namespace App\Support;

/**
 * Catalogue des variables de paie mensuelles (ancien fichier Excel « VARIABLES »).
 *
 * `colonne` = position (0 = colonne A) dans le fichier Excel : les en-têtes de
 * la ligne 1 sont parfois perdus (cellules fusionnées), les positions, elles,
 * sont identiques dans tous les mois.
 * `hebdo` = variable saisie sur 5 semaines (5 colonnes consécutives).
 */
class CatalogueVariablesPaie
{
    public const UNITE_JOUR = 'jour';
    public const UNITE_HEURE = 'heure';
    public const UNITE_MONTANT = 'montant';

    public const CODES = [
        'INDICATEUR_E'       => ['libelle' => 'Colonne E (sans en-tête, à confirmer)', 'unite' => self::UNITE_JOUR,    'colonne' => 4,  'visible' => false],
        'ABSENCE_INJUSTIFIEE'=> ['libelle' => 'Absence injustifiée',                   'unite' => self::UNITE_JOUR,    'colonne' => 5,  'visible' => true],
        'ABSENCE_INJUSTIFIEE_2'=> ['libelle' => 'Absence injustifiée (2)',             'unite' => self::UNITE_JOUR,    'colonne' => 6,  'visible' => false],
        'ABSENCE_JUSTIFIEE'  => ['libelle' => 'Absence justifiée',                     'unite' => self::UNITE_JOUR,    'colonne' => 10, 'visible' => true, 'hebdo' => true],
        'ARRET_MALADIE'      => ['libelle' => 'Arrêt maladie',                         'unite' => self::UNITE_JOUR,    'colonne' => 15, 'visible' => true, 'hebdo' => true],
        'HS_15_SAMEDI'       => ['libelle' => 'HS 15 % + samedi',                      'unite' => self::UNITE_HEURE,   'colonne' => 20, 'visible' => true, 'hebdo' => true],
        'MUTUELLE'           => ['libelle' => 'Mutuelle à prélever',                   'unite' => self::UNITE_JOUR,    'colonne' => 25, 'visible' => true],
        'RAPPEL_JOURS'       => ['libelle' => 'Rappel en jours à payer',               'unite' => self::UNITE_JOUR,    'colonne' => 26, 'visible' => true],
        'RAPPEL_SALAIRE'     => ['libelle' => 'Rappel salaire',                        'unite' => self::UNITE_JOUR,    'colonne' => 27, 'visible' => true],
        'RAPPEL_COL_28'      => ['libelle' => 'Colonne 28 (sans en-tête, à confirmer)','unite' => self::UNITE_JOUR,    'colonne' => 28, 'visible' => false],
        'HS_75'              => ['libelle' => 'HS 75 %',                               'unite' => self::UNITE_HEURE,   'colonne' => 29, 'visible' => true],
        'HS_100'             => ['libelle' => 'HS 100 %',                              'unite' => self::UNITE_HEURE,   'colonne' => 30, 'visible' => true],
        'PRIME_NUIT'         => ['libelle' => 'Prime de nuit',                         'unite' => self::UNITE_HEURE,   'colonne' => 31, 'visible' => true],
        'DEJA_PERCU'         => ['libelle' => 'Déjà perçu',                            'unite' => self::UNITE_MONTANT, 'colonne' => 32, 'visible' => true],
        'ALLOCATION_FAMILIALE'=> ['libelle' => 'Allocation familiale',                 'unite' => self::UNITE_MONTANT, 'colonne' => 33, 'visible' => true],
        'PRIME_TRANSPORT'    => ['libelle' => 'Prime de transport',                    'unite' => self::UNITE_MONTANT, 'colonne' => 34, 'visible' => true],
        'PRIME_RESPONSABILITE'=> ['libelle' => 'Prime de responsabilité',              'unite' => self::UNITE_MONTANT, 'colonne' => 35, 'visible' => true],
        'SANCTION'           => ['libelle' => 'Sanction disciplinaire',                'unite' => self::UNITE_JOUR,    'colonne' => 36, 'visible' => true],
        'PRIME_ANCIENNETE'   => ['libelle' => 'Prime d\'ancienneté',                   'unite' => self::UNITE_MONTANT, 'colonne' => 37, 'visible' => true],
        'REMBOURSEMENT_PRET' => ['libelle' => 'Remboursement prêt',                    'unite' => self::UNITE_MONTANT, 'colonne' => 38, 'visible' => true],
        'PRIME_RENDEMENT'    => ['libelle' => 'Prime de rendement',                    'unite' => self::UNITE_MONTANT, 'colonne' => 39, 'visible' => true],
        'RAPPEL_NON_IMPOSABLE'=> ['libelle' => 'Rappel salaire non imposable',         'unite' => self::UNITE_MONTANT, 'colonne' => 40, 'visible' => true],
    ];

    public const NB_SEMAINES = 5;

    /** Dernière colonne lue dans le fichier (index 0 = A). */
    public static function derniereColonne(): int
    {
        return max(array_column(self::CODES, 'colonne'));
    }

    public static function libelle(string $code): string
    {
        return self::CODES[$code]['libelle'] ?? $code;
    }

    /** Codes affichés dans l'application (les colonnes à confirmer sont importées mais masquées). */
    public static function visibles(): array
    {
        return array_keys(array_filter(self::CODES, fn ($c) => $c['visible']));
    }
}
