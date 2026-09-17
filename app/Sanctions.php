<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sanctions extends Model
{
    protected $table = 'e_sanction';

    public function demandeur()
    {
        return $this->belongsTo(Travailleur::class, 'demandeurid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
