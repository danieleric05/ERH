<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    protected $table = 'e_candidat';
    protected $fillable = [
        'nom', 'prenom', 'civilite', 'date_naissance', 'lieu_naissance',
        'nationalite_id', 'telephone', 'email', 'adresse', 'niveau_etude_id',
        'annee_experience', 'cv_path', 'lettre_motivation_path', 'photo_path', 'source'
    ];
    protected $dates = ['date_naissance'];

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'candidat_id');
    }

    public function nationalite()
    {
        return $this->belongsTo(Pays::class, 'nationalite_id');
    }

    public function niveauEtude()
    {
        return $this->belongsTo(NiveauEtude::class, 'niveau_etude_id');
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
