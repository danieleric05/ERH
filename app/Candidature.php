<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $table = 'e_candidature';
    protected $fillable = [
        'candidat_id', 'offre_emploi_id', 'statut_id', 'date_candidature',
        'score_evaluation', 'notes', 'rejetee_raison', 'date_changement_statut', 'assignee_a'
    ];
    protected $dates = ['date_candidature', 'date_changement_statut'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }

    public function offre()
    {
        return $this->belongsTo(OffreEmploi::class, 'offre_emploi_id');
    }

    public function statut()
    {
        return $this->belongsTo(StatutCandidature::class, 'statut_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_a');
    }

    public function entretiens()
    {
        return $this->hasMany(Entretien::class, 'candidature_id');
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationCandidat::class, 'candidature_id');
    }
}
