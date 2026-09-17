<?php

namespace App\Console\Commands;

use App\Travailleur;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CorrigerStatutsTravailleurs extends Command
{
    protected $signature = 'erh:corriger-statuts-travailleurs {--apply : Écrit réellement les changements (par défaut : rapport à sec, lecture seule)}';

    protected $description = "Recalcule le statut actif/cessé des travailleurs selon la nouvelle règle (voir /home/daniel/.claude/plans/ethereal-jingling-coral.md) et corrige les incohérences de données trouvées : (A) des travailleurs à etapeid=3 sans vraie cessation confirmée qui devraient être actifs (embauche récente ou fin de contrat récente non suivie d'une cessation réelle), (B) des travailleurs à statutid=1 alors que leur etapeid montre qu'ils sont déjà actifs — désalignement hérité d'avant la migration, et (C) des travailleurs avec une vraie cessation confirmée (e_action_cdc ou motif de départ non ambigu) mais dont etapeid affiche pourtant un état actif — une vraie cessation confirmée doit toujours l'emporter, quelle que soit la date d'embauche. Voir Travailleur::cessesConfirmes() pour la définition partagée avec la logique d'affichage (scopeActif/scopeCesseConfirme).";

    public function handle(): int
    {
        $apply = $this->option('apply');

        $cessesConfirmes = Travailleur::cessesConfirmes();

        // Catégorie A : etapeid=3 SANS vraie cessation confirmée, qui devraient redevenir actifs.
        $candidatsA = Travailleur::where('etapeid', 3)
            ->whereNotIn('id', $cessesConfirmes)
            ->where(function ($q) {
                $q->where('date_debut_contrat', '>', '2024-12-31')
                  ->orWhere('date_fin_contrat', '>=', '2025-01-01');
            })
            ->get();

        // Catégorie B : statutid=1 alors que etapeid indique déjà un dossier actif/avancé.
        $candidatsB = Travailleur::where('statutid', 1)
            ->whereIn('etapeid', [2, 5, 6])
            ->get();

        // Catégorie C : vraie cessation confirmée (e_action_cdc non réversée, ou motif de départ
        // non ambigu) mais etapeid affiche un état actif — la cessation réelle doit toujours
        // l'emporter, quelle que soit la date d'embauche.
        $candidatsC = Travailleur::whereIn('id', $cessesConfirmes)
            ->where('etapeid', '!=', 3)
            ->where('etapeid', '!=', 4)
            ->get();

        $this->info("📊 CATÉGORIE A — etapeid=3 à reclasser en actif (etapeid=2) : {$candidatsA->count()}");
        foreach ($candidatsA as $t) {
            $regle1 = $t->date_debut_contrat > '2024-12-31';
            $regle = $regle1 ? 'règle 1 (embauche récente)' : 'règle 2 (fin de contrat récente)';
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  embauche={$t->date_debut_contrat}  fin={$t->date_fin_contrat}  [{$regle}]");
        }

        $this->newLine();
        $this->info("📊 CATÉGORIE B — statutid=1 à corriger en statutid=2 (etapeid déjà actif) : {$candidatsB->count()}");
        foreach ($candidatsB as $t) {
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  etapeid={$t->etapeid}");
        }

        $this->newLine();
        $this->info("📊 CATÉGORIE C — vraie cessation confirmée mais etapeid actif, à reclasser en cessation (etapeid=3) : {$candidatsC->count()}");
        foreach ($candidatsC as $t) {
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  etapeid actuel={$t->etapeid}  motif={$t->motif_fin_contrat}");
        }

        if (!$apply) {
            $this->newLine();
            $this->info('Rapport à sec (--apply non passé) : rien n\'a été écrit.');
            return self::SUCCESS;
        }

        $this->newLine();
        $total = $candidatsA->count() + $candidatsB->count() + $candidatsC->count();
        if (!$this->confirm("Appliquer réellement ces {$total} corrections ?")) {
            $this->info('Annulé.');
            return self::SUCCESS;
        }

        foreach ($candidatsA as $t) {
            $t->etapeid = 2;
            $t->save();
            Log::info("Correction statut actif (catégorie A): travailleur {$t->id} ({$t->matricule}) etapeid remis à 2");
        }

        foreach ($candidatsB as $t) {
            $t->statutid = 2;
            $t->save();
            Log::info("Correction statut actif (catégorie B): travailleur {$t->id} ({$t->matricule}) statutid remis à 2");
        }

        foreach ($candidatsC as $t) {
            $t->etapeid = 3;
            $t->save();
            Log::info("Correction statut actif (catégorie C): travailleur {$t->id} ({$t->matricule}) etapeid remis à 3 (cessation confirmée)");
        }

        $this->info("✅ {$candidatsA->count()} correction(s) catégorie A, {$candidatsB->count()} catégorie B, {$candidatsC->count()} catégorie C appliquées.");

        return self::SUCCESS;
    }
}
