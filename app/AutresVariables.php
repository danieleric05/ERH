<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutresVariables extends Model
{
    protected $table = 'e_autres_variables';

    public const CAS = [
        1 => 'RAPPEL SALAIRE',
        2 => 'PRIME DE RESPONSABILITE',
        3 => 'COMPLEMENT GRATIFICATION',
        4 => 'LOYER MENSUEL',
        5 => 'REMBOURSEMENT PRET',
    ];

    /** Identifiants des travailleurs concernés (liste sérialisée, parfois vide). */
    public function getEmployesAttribute(): array
    {
        return Variables::deserialiser($this->employeid);
    }

    public function getCasLibelleAttribute(): string
    {
        return self::CAS[$this->cas] ?? '-';
    }
}
