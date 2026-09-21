<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Travailleur extends Model
{
    protected $table = 'e_travailleur';

    /**
     * URL de téléchargement direct du contrat PDF
     *
     * @return string
     */
    public function getContratUrlAttribute(): string
    {
        if ($this->idtype_contrat == 2) {
            return route('telechargerContratCDD', ['id' => $this->id, 'download' => 'pdf']);
        } elseif ($this->idtype_contrat == 3) {
            return route('telechargerContratCDI', ['id' => $this->id, 'download' => 'pdf']);
        } else {
            return route('telechargerContratJournalier', ['id' => $this->id, 'download' => 'pdf']);
        }
    }

    /**
     * Libellé lisible du type de contrat
     *
     * @return string
     */
    public function getContratLibelleAttribute(): string
    {
        if ($this->idtype_contrat == 2) {
            return 'Contrat CDD';
        } elseif ($this->idtype_contrat == 3) {
            return 'Contrat CDI';
        } else {
            return 'Contrat Journalier';
        }
    }

    /**
     * Prénoms complets. Sage coupe le prénom à 19 caractères : dans ce cas
     * prenom_suite prolonge le mot coupé (aucune espace). Sinon (données
     * historiques) prenom_suite est un prénom distinct, séparé par une espace.
     *
     * @return string
     */
    public function getPrenomsCompletsAttribute(): string
    {
        $prenom = (string) $this->prenom;
        $suite  = trim((string) $this->prenom_suite);

        if ($suite === '' || strtoupper($suite) === 'NULL') {
            return $prenom;
        }

        return $prenom . (mb_strlen($prenom) >= 19 ? '' : ' ') . $suite;
    }
}
