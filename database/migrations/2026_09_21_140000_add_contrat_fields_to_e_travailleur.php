<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Informations du « Répertoire CDD » qui n'avaient pas de place sur la fiche :
     * période d'essai, durée, historique des CDD, évaluateur, observation, etc.
     * Contrat en cours uniquement : l'historique des contrats reste dans e_action_cdc.
     */
    public function up(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->string('nature_piece', 30)->nullable()->comment("Nature de la pièce d'identité (CNI, passeport…)");
            $table->string('lieu_habitation', 100)->nullable();
            $table->unsignedTinyInteger('mois_essai')->nullable()->comment("Durée de la période d'essai en mois");
            $table->date('date_fin_essai')->nullable();
            $table->unsignedSmallInteger('duree_mois')->nullable()->comment('Durée du contrat en cours, en mois');
            $table->date('ancienne_date_fin')->nullable()->comment('Date de fin du contrat précédent');
            $table->unsignedTinyInteger('nbre_cdd')->nullable()->comment('Nombre de CDD successifs');
            $table->date('date_entree')->nullable()->comment("Date d'entrée dans l'entreprise (première embauche)");
            $table->date('debut_journalier')->nullable()->comment("Début comme journalier avant l'embauche");
            $table->string('evaluateur', 100)->nullable();
            $table->text('observation')->nullable();
            $table->string('salaire_lettres', 200)->nullable();
            $table->boolean('transport_inclus')->nullable()->comment('1 = prime de transport incluse dans le salaire');
        });
    }

    public function down(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropColumn(['nature_piece', 'lieu_habitation', 'mois_essai', 'date_fin_essai', 'duree_mois', 'ancienne_date_fin',
                'nbre_cdd', 'date_entree', 'debut_journalier', 'evaluateur', 'observation', 'salaire_lettres', 'transport_inclus']);
        });
    }
};
