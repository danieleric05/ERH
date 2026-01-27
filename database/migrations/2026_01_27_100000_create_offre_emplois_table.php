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
        Schema::create('e_offre_emploi', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->unsignedBigInteger('departement_id')->nullable();
            $table->unsignedBigInteger('fonction_id')->nullable();
            $table->unsignedBigInteger('unite_id')->nullable();
            $table->enum('type_contrat', ['CDD', 'CDI', 'Journalier'])->default('CDD');
            $table->longText('description')->nullable();
            $table->text('competences_requises')->nullable();
            $table->string('experience_requise')->nullable();
            $table->decimal('salaire_min', 10, 2)->nullable();
            $table->decimal('salaire_max', 10, 2)->nullable();
            $table->integer('nombre_postes')->default(1);
            $table->date('date_publication')->nullable();
            $table->date('date_cloture')->nullable();
            $table->tinyInteger('statut')->default(1)->comment('1=Publiée, 2=Clôturée, 3=Pourvue');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_offre_emploi');
    }
};
