<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autorisations extends Model
{
    protected $table = 'e_autorisation';

    public function travailleur()
    {
        return $this->belongsTo(Travailleur::class, 'demandeurid');
    }
}
