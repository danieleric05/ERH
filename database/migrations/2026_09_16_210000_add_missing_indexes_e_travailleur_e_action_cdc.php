<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * e_travailleur (matricule/etapeid/statutid) et e_action_cdc (travailleurid/actionid/
     * date_choisit) n'avaient aucun index hors clé primaire, malgré des filtres systématiques
     * sur ces colonnes partout dans l'app. La sous-requête corrélée de
     * Travailleur::cessesConfirmes() (scopeActif/scopeCesseConfirme) en particulier faisait un
     * scan complet de e_action_cdc à chaque ligne : 13,6s -> 58ms sur la liste des actifs après
     * ajout de ces index (mesuré en staging, ~9000 travailleurs / ~19000 lignes e_action_cdc).
     */
    public function up(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->index('matricule');
            $table->index('etapeid');
            $table->index('statutid');
        });

        Schema::table('e_action_cdc', function (Blueprint $table) {
            $table->index(['travailleurid', 'actionid', 'date_choisit']);
        });
    }

    public function down(): void
    {
        Schema::table('e_travailleur', function (Blueprint $table) {
            $table->dropIndex(['matricule']);
            $table->dropIndex(['etapeid']);
            $table->dropIndex(['statutid']);
        });

        Schema::table('e_action_cdc', function (Blueprint $table) {
            $table->dropIndex(['travailleurid', 'actionid', 'date_choisit']);
        });
    }
};
