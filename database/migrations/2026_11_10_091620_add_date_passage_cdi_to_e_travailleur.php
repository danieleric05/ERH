<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Date du passage de CDD à CDI (remplie automatiquement par le modèle Travailleur).
     */
    public function up(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->date('date_passage_cdi')->nullable()->comment('Date du passage de CDD à CDI');
        });
    }

    public function down(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropColumn('date_passage_cdi');
        });
    }
};
