<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariablePaie extends Model
{
    protected $table = 'e_variable_paie';

    protected $guarded = ['id'];

    protected $casts = [
        'valeur' => 'float',
    ];

    public function travailleur()
    {
        return $this->belongsTo(Travailleur::class, 'travailleurid');
    }
}
