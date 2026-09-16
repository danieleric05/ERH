<?php

namespace App\Console\Commands;

use App\ActionsCDC;
use App\Travailleur;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CesserContratsExpires extends Command
{
    protected $signature = 'erh:cesser-contrats-expires {--apply : Écrit réellement les changements (par défaut : rapport à sec, lecture seule)}';

    protected $description = "Cesse automatiquement (etapeid=3) tout travailleur à contrat à durée déterminée (Journalier, CDD, Stage) dont la date de fin de contrat est dépassée et qui n'a pas été reconduit entre-temps. Le CDI (idtype_contrat=3), sans date de fin naturelle, n'est jamais concerné. Une vraie ligne e_action_cdc(actionid=3) est créée pour garder l'historique, comme pour une cessation manuelle. Destiné à tourner quotidiennement via le scheduler Laravel (voir app/Console/Kernel.php).";

    /** Compte administratif utilisé pour l'attribution des cessations automatiques (userid NOT NULL sur e_action_cdc). */
    private const USERID_SYSTEME = 11; // DRH

    public function handle(): int
    {
        $apply = $this->option('apply');
        $aujourdhui = Carbon::today()->toDateString();

        $candidats = Travailleur::where('idtype_contrat', '!=', 3) // exclut le CDI, sans échéance naturelle
            ->where('etapeid', '!=', 3)
            ->where('etapeid', '!=', 4)
            ->whereNotNull('date_fin_contrat')
            ->where('date_fin_contrat', '>', '0001-01-01') // exclut les dates placeholder vides
            ->where('date_fin_contrat', '<', $aujourdhui)
            ->get();

        $this->info("📊 Contrats arrivés à échéance à cesser automatiquement : {$candidats->count()}");
        foreach ($candidats as $t) {
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  fin_contrat={$t->date_fin_contrat}  etapeid_actuel={$t->etapeid}");
        }

        if (!$apply) {
            $this->newLine();
            $this->info('Rapport à sec (--apply non passé) : rien n\'a été écrit.');
            return self::SUCCESS;
        }

        foreach ($candidats as $t) {
            $t->etapeid = 3;
            $t->motif_fin_contrat = 'FIN DE CONTRAT (cessation automatique à échéance)';
            $t->userid = self::USERID_SYSTEME;
            $t->updated_at = Carbon::now();
            $t->save();

            $action = new ActionsCDC();
            $action->date_choisit = $t->date_fin_contrat;
            $action->debut_contrat = $t->date_debut_contrat;
            $action->fin_contrat = $t->date_fin_contrat;
            $action->travailleurid = $t->id;
            $action->userid = self::USERID_SYSTEME;
            $action->actionid = 3;
            $action->save();

            Log::info("Cessation automatique à échéance: travailleur {$t->id} ({$t->matricule}), fin de contrat {$t->date_fin_contrat}");
        }

        $this->info("✅ {$candidats->count()} travailleur(s) cessé(s) automatiquement.");

        return self::SUCCESS;
    }
}
