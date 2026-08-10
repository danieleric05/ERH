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
        Schema::create('e_conges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('travailleurid');
            $table->unsignedTinyInteger('type_conge'); // 1: annuel, 2: maladie, 3: maternite/paternite, 4: sans solde, 5: exceptionnel
            $table->date('debut');
            $table->date('fin');
            $table->date('date_reprise');
            $table->unsignedInteger('nombre_jours')->nullable();
            $table->text('justification')->nullable();
            $table->unsignedTinyInteger('statutid')->default(1); // 1: en attente, 2: valide, 3: refuse
            $table->unsignedInteger('userid')->nullable();
            $table->unsignedTinyInteger('mois')->nullable();
            $table->unsignedSmallInteger('annee')->nullable();
            $table->timestamps();

            $table->index('travailleurid');
            $table->index('statutid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_conges');
    }
};
