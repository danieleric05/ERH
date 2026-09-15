<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Unité d'affectation d'un utilisateur ERH (table `unite`).
 * À ne pas confondre avec App\Unites (table `e_unite`), qui sert
 * uniquement aux travailleurs et utilise des identifiants différents.
 */
class Unite extends Model
{
    public $timestamps = false;

    protected $table = 'unite';

    protected $fillable = ['label', 'code', 'description'];
}
