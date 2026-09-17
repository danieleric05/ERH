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
        Schema::create('e_candidat', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('civilite', ['M', 'Mme', 'Mlle'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->integer('nationalite_id')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email');
            $table->text('adresse')->nullable();
            $table->integer('niveau_etude_id')->nullable();
            $table->integer('annee_experience')->default(0);
            $table->string('cv_path')->nullable();
            $table->string('lettre_motivation_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('source')->nullable()->comment('Site, LinkedIn, Référence, etc.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_candidat');
    }
};
