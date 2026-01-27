<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffreEmploi extends Model
{
    protected $table = 'e_offre_emploi';
    protected $fillable = [
        'titre', 'departement_id', 'fonction_id', 'unite_id', 'type_contrat',
        'description', 'competences_requises', 'experience_requise',
        'salaire_min', 'salaire_max', 'nombre_postes',
        'date_publication', 'date_cloture', 'statut', 'created_by'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function fonction()
    {
        return $this->belongsTo(Fonction::class, 'fonction_id');
    }

    public function unite()
    {
        return $this->belongsTo(Unites::class, 'unite_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'offre_emploi_id');
    }
}
