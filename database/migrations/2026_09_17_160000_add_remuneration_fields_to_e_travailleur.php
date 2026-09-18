<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Champs de rémunération pour les contrats CDD/CDI. Deux structures
     * possibles constatées sur les contrats réels : (1) un salaire net
     * unique, ou (2) un salaire de base + un sursalaire séparé. Les deux
     * ont en commun une prime de transport nette. Le Journalier n'utilise
     * pas ces champs (montants fixes SMIG codés en dur dans son contrat).
     */
    public function up(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->tinyInteger('type_remuneration')->unsigned()->nullable()
                ->comment('1 = Salaire net (montant unique), 2 = Salaire de base + sursalaire');
            $table->decimal('salaire_base', 15, 2)->nullable()
                ->comment('Salaire net si type_remuneration=1, salaire de base si type_remuneration=2');
            $table->decimal('sursalaire', 15, 2)->nullable();
            $table->decimal('prime_transport', 15, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropColumn(['type_remuneration', 'salaire_base', 'sursalaire', 'prime_transport']);
        });
    }
};
