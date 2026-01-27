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
        Schema::create('e_candidature', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->unsignedBigInteger('offre_emploi_id');
            $table->unsignedBigInteger('statut_id')->default(1);
            $table->date('date_candidature')->useCurrent();
            $table->integer('score_evaluation')->nullable()->comment('0-100');
            $table->longText('notes')->nullable();
            $table->string('rejetee_raison')->nullable();
            $table->dateTime('date_changement_statut')->nullable();
            $table->unsignedInteger('assignee_a')->nullable()->comment('user_id du recruteur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_candidature');
    }
};
