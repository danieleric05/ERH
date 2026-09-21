<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Variables de paie mensuelles (remplace le fichier Excel « VARIABLES »).
     * Une ligne = une valeur (jours, heures ou montant) pour un travailleur,
     * un mois, un code de variable et, pour les variables hebdomadaires, une
     * semaine (1 à 5). Le matricule est conservé même si le travailleur est
     * introuvable en base, pour ne rien perdre à l'import.
     */
    public function up(): void
    {
        Schema::create('e_variable_paie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travailleurid')->nullable()->index();
            $table->string('matricule', 20)->index();
            $table->unsignedSmallInteger('annee');
            $table->unsignedTinyInteger('mois');
            $table->string('code', 30);
            $table->unsignedTinyInteger('semaine')->default(0)->comment('0 = variable mensuelle, 1..5 = semaine');
            $table->string('periode_libelle', 60)->nullable()->comment('Libellé de la période tel que saisi (ex. DU 24 AU 29 AOUT 26)');
            $table->decimal('valeur', 12, 2)->default(0);
            $table->string('source', 12)->default('manuel')->comment('import | manuel');
            $table->unsignedBigInteger('userid')->nullable();
            $table->timestamps();

            $table->unique(['matricule', 'annee', 'mois', 'code', 'semaine'], 'uniq_variable_paie');
            $table->index(['annee', 'mois', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_variable_paie');
    }
};
