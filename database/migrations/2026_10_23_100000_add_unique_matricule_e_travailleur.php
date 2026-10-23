<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ferme la course d'enregistrement simultané de l'étape 1 (EmployerController@post_travailleur) :
     * deux utilisateurs ne peuvent plus créer le même matricule. Remplace l'index simple posé par
     * 2026_09_16_210000. S'arrête sans rien modifier s'il reste des doublons à traiter à la main
     * (voir verifier_doublons_matricule.sql).
     */
    public function up(): void
    {
        $doublons = DB::table('e_travailleur')
            ->select('matricule', DB::raw('COUNT(*) as n'))
            ->groupBy('matricule')
            ->havingRaw('COUNT(*) > 1')
            ->limit(20)
            ->get();

        if ($doublons->isNotEmpty()) {
            throw new RuntimeException(
                'Doublons de matricule dans e_travailleur, migration annulée : '
                . $doublons->map(fn ($d) => "{$d->matricule} (x{$d->n})")->implode(', ')
            );
        }

        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropIndex(['matricule']);
            $table->unique('matricule');
        });
    }

    public function down(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->index('matricule');
        });
    }
};
