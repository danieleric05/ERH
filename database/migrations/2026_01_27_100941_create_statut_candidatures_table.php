<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('e_statut_candidature', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('couleur')->default('#6366f1');
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // Insérer les statuts par défaut
        DB::table('e_statut_candidature')->insert([
            ['libelle' => 'Reçu', 'couleur' => '#94a3b8', 'ordre' => 1],
            ['libelle' => 'Présélectionné', 'couleur' => '#3b82f6', 'ordre' => 2],
            ['libelle' => 'Entretien', 'couleur' => '#8b5cf6', 'ordre' => 3],
            ['libelle' => 'Offre', 'couleur' => '#f59e0b', 'ordre' => 4],
            ['libelle' => 'Embauché', 'couleur' => '#10b981', 'ordre' => 5],
            ['libelle' => 'Rejeté', 'couleur' => '#ef4444', 'ordre' => 6],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_statut_candidature');
    }
};
