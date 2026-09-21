<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    protected $table = 'e_variable';

    public const JOURS = [1 => 'DIMANCHE', 2 => 'FERIE', 3 => 'JOUR OUVRABLE'];
    public const CAS = [1 => "RETARD D'ENROLEMENT", 2 => 'DEFAUT DE POINTAGE', 3 => 'OUBLI DE POINTAGE', 4 => "DEFAUT D'EMPREINTE"];

    /** Valeurs « TOUS » des formulaires de recherche. */
    public const TOUS_JOURS = 4;
    public const TOUS_CAS = 5;

    /** Lecture sûre d'une liste sérialisée (null, chaîne vide ou valeur corrompue => tableau vide). */
    public static function deserialiser($valeur): array
    {
        if (!is_string($valeur) || $valeur === '') {
            return [];
        }
        $liste = @unserialize($valeur, ['allowed_classes' => false]);

        return is_array($liste) ? array_values($liste) : [];
    }

    /** Matricules concernés par une variable manuelle (cause 1). */
    public function getMatriculesAttribute(): array
    {
        return self::deserialiser($this->travailleurid);
    }

    /** Identifiants des travailleurs concernés par des heures supplémentaires (cause 2). */
    public function getEmployesHsAttribute(): array
    {
        return self::deserialiser($this->employer_hs);
    }

    public function getJourLibelleAttribute(): ?string
    {
        return self::JOURS[$this->type_variable] ?? null;
    }

    public function getCasLibelleAttribute(): ?string
    {
        return self::CAS[$this->cas_variables] ?? null;
    }

    /** Variables manuelles (pointage) sur une période, avec filtres facultatifs ("TOUS" = pas de filtre). */
    public function scopePointage($query, $debut, $fin, $jour = null, $cas = null)
    {
        $query->where('cause', 1)->where('debut', '>=', $debut)->where('debut', '<=', $fin);
        if ($jour !== null && $jour !== '' && (int) $jour !== self::TOUS_JOURS) {
            $query->where('type_variable', $jour);
        }
        if ($cas !== null && $cas !== '' && (int) $cas !== self::TOUS_CAS) {
            $query->where('cas_variables', $cas);
        }

        return $query;
    }
}
