<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatutCandidature extends Model
{
    protected $table = 'e_statut_candidature';
    protected $fillable = ['libelle', 'couleur', 'ordre'];

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'statut_id');
    }
}
