<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DedupliquerMatriculesTravailleurs extends Command
{
    protected $signature = 'travailleurs:dedupliquer-matricules {--force : Écrit réellement (par défaut : essai à blanc, lecture seule)}';

    protected $description = "Dédoublonne e_travailleur par matricule : garde la ligne au statutid le plus élevé, puis etapeid, puis id ; rattache ou supprime les lignes liées des perdantes ; sauvegarde dans bak_dedup_<table>_<YYYYMMDD>. Les groupes ambigus (égalité statutid/etapeid et dates de contrat différentes) sont listés et ignorés.";

    /** Colonnes entières désignant un travailleur par id. */
    private const COLONNES_ID = ['travailleurid', 'travailleur_id', 'id_travailleur', 'idtravailleur'];

    /** Colonnes texte désignant un travailleur par matricule (jamais modifiées). */
    private const COLONNES_MATRICULE = ['matricule', 'travailleur_mat', 'matricule_travailleur'];

    /** @var list<array{table:string,col:string}> */
    private array $liensId = [];

    /** @var list<string> */
    private array $liensMatricule = [];

    /** @var list<string> */
    private array $liensNonGeres = [];

    /** @var array<string,list<string>> colonnes comparables (hors PK, hors colonne de lien) par table */
    private array $colonnesTable = [];

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $this->decouvrirLiens();

        [$groupes, $ambigus] = $this->construireGroupes();

        $this->info('Base : ' . DB::getDatabaseName());
        $lignes = [];
        foreach ($groupes as $m => $g) {
            $lignes[] = [$m, $g['garde'], implode(',', $g['perdants']), 'non'];
        }
        foreach ($ambigus as $m => $ids) {
            $lignes[] = [$m, '-', implode(',', $ids), 'OUI (ignoré)'];
        }
        $this->table(['matricule', 'id gardé', 'ids supprimables / concernés', 'ambigu'], $lignes);

        $totaux = $this->calculerTotaux($groupes);
        $rows = [];
        foreach ($totaux as $k => $t) {
            $rows[] = [$k, $t['rattacher'], $t['supprimer']];
        }
        $this->table(['table.colonne', 'à rattacher', 'à supprimer'], $rows);

        if ($this->liensMatricule) {
            $this->line('Liées par matricule texte (non modifiées) : ' . implode(', ', $this->liensMatricule));
        }
        if ($this->liensNonGeres) {
            $this->warn('Colonnes texte non gérées (à vérifier à la main) : ' . implode(', ', $this->liensNonGeres));
        }

        $nbPerdants = array_sum(array_map(fn ($g) => count($g['perdants']), $groupes));
        $this->info(sprintf(
            'Synthèse : %d groupe(s) traitable(s), %d ambigu(s), %d ligne(s) e_travailleur à supprimer, %d ligne(s) liée(s) à rattacher, %d à supprimer.',
            count($groupes), count($ambigus), $nbPerdants,
            array_sum(array_column($totaux, 'rattacher')), array_sum(array_column($totaux, 'supprimer'))
        ));

        if (!$force) {
            $this->info("Essai à blanc (--force non passé) : rien n'a été écrit.");
            return self::SUCCESS;
        }
        if (!$groupes) {
            $this->info('Rien à faire.');
            return self::SUCCESS;
        }
        if (!$this->confirm("Appliquer réellement sur la base " . DB::getDatabaseName() . " ({$nbPerdants} ligne(s) e_travailleur supprimée(s)) ?")) {
            $this->info('Annulé.');
            return self::SUCCESS;
        }

        return $this->appliquer($groupes, $ambigus);
    }

    private function decouvrirLiens(): void
    {
        $db = DB::getDatabaseName();
        $cols = DB::select(
            "SELECT c.TABLE_NAME t, c.COLUMN_NAME c, c.DATA_TYPE d FROM information_schema.COLUMNS c
             JOIN information_schema.TABLES x ON x.TABLE_SCHEMA = c.TABLE_SCHEMA AND x.TABLE_NAME = c.TABLE_NAME AND x.TABLE_TYPE = 'BASE TABLE'
             WHERE c.TABLE_SCHEMA = ? AND c.TABLE_NAME NOT LIKE 'bak\\_dedup\\_%' ORDER BY c.TABLE_NAME, c.ORDINAL_POSITION",
            [$db]
        );
        $fk = [];
        foreach (DB::select(
            "SELECT TABLE_NAME t, COLUMN_NAME c FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND REFERENCED_TABLE_NAME = 'e_travailleur'", [$db]
        ) as $r) {
            $fk[$r->t . '.' . $r->c] = true;
        }
        $entiers = ['int', 'bigint', 'mediumint', 'smallint', 'tinyint'];
        foreach ($cols as $c) {
            if ($c->t === 'e_travailleur') {
                continue;
            }
            $nom = strtolower($c->c);
            $cle = $c->t . '.' . $c->c;
            if (in_array($nom, self::COLONNES_ID, true) || isset($fk[$cle])) {
                if (in_array($c->d, $entiers, true)) {
                    $this->liensId[] = ['table' => $c->t, 'col' => $c->c];
                } else {
                    $this->liensNonGeres[] = "$cle ({$c->d})";
                }
            } elseif (in_array($nom, self::COLONNES_MATRICULE, true)) {
                $this->liensMatricule[] = $cle;
            } elseif ($nom === 'employeid') {
                $this->liensNonGeres[] = "$cle ({$c->d})";
            }
        }
        foreach (array_unique(array_column($this->liensId, 'table')) as $t) {
            $pk = DB::select("SELECT COLUMN_NAME c FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = 'PRIMARY'", [$db, $t]);
            $pk = array_map(fn ($r) => $r->c, $pk);
            $this->colonnesTable[$t] = [];
            foreach ($cols as $c) {
                // created_at/updated_at ignorés : deux lignes identiques à l'horodatage près restent des doublons
                if ($c->t === $t && !in_array($c->c, $pk, true) && !in_array($c->c, ['created_at', 'updated_at'], true)) {
                    $this->colonnesTable[$t][] = $c->c;
                }
            }
        }
    }

    /** @return array{0:array<string,array{garde:int,perdants:list<int>}>,1:array<string,list<int>>} */
    private function construireGroupes(): array
    {
        $groupes = [];
        $ambigus = [];
        $matricules = DB::table('e_travailleur')->whereNotNull('matricule')->where('matricule', '!=', '')
            ->groupBy('matricule')->havingRaw('COUNT(*) > 1')->pluck('matricule');
        foreach ($matricules as $m) {
            $rows = DB::table('e_travailleur')->where('matricule', $m)
                ->orderByDesc('statutid')->orderByDesc('etapeid')->orderByDesc('id')->get();
            $tete = $rows->filter(fn ($r) => $r->statutid == $rows[0]->statutid && $r->etapeid == $rows[0]->etapeid);
            $datesDiffent = $tete->count() > 1 && $tete->map(fn ($r) => $r->date_debut_contrat . '|' . $r->date_fin_contrat)->unique()->count() > 1;
            if ($datesDiffent) {
                $ambigus[$m] = $rows->pluck('id')->map(fn ($i) => (int) $i)->all();
                continue;
            }
            $groupes[$m] = [
                'garde' => (int) $rows[0]->id,
                'perdants' => $rows->slice(1)->pluck('id')->map(fn ($i) => (int) $i)->all(),
            ];
        }
        return [$groupes, $ambigus];
    }

    /** Condition SQL : une ligne $k (gardée) identique à $l (perdante) hors colonne de lien. */
    private function conditionDoublon(string $table, string $col): string
    {
        $conds = ['1 = 1'];
        foreach ($this->colonnesTable[$table] as $c) {
            if ($c !== $col) {
                $conds[] = "k.`$c` <=> l.`$c`";
            }
        }
        return implode(' AND ', $conds);
    }

    /** @return array<string,array{rattacher:int,supprimer:int}> */
    private function calculerTotaux(array $groupes): array
    {
        $totaux = [];
        foreach ($this->liensId as $lien) {
            ['table' => $t, 'col' => $col] = $lien;
            $tot = ['rattacher' => 0, 'supprimer' => 0];
            $cond = $this->conditionDoublon($t, $col);
            foreach ($groupes as $g) {
                $in = implode(',', array_fill(0, count($g['perdants']), '?'));
                $total = DB::selectOne("SELECT COUNT(*) n FROM `$t` WHERE `$col` IN ($in)", $g['perdants'])->n;
                $dup = DB::selectOne(
                    "SELECT COUNT(*) n FROM `$t` l WHERE l.`$col` IN ($in) AND EXISTS (SELECT 1 FROM `$t` k WHERE k.`$col` = ? AND $cond)",
                    array_merge($g['perdants'], [$g['garde']])
                )->n;
                $tot['supprimer'] += $dup;
                $tot['rattacher'] += $total - $dup;
            }
            $totaux["$t.$col"] = $tot;
        }
        return $totaux;
    }

    private function appliquer(array $groupes, array $ambigus): int
    {
        $suffixe = date('Ymd');
        $perdants = array_merge(...array_column($groupes, 'perdants'));
        $in = implode(',', $perdants);

        // CREATE TABLE ... SELECT provoque un commit implicite en MySQL : les sauvegardes se font AVANT la transaction.
        $sauvegardes = ['e_travailleur' => "`id` IN ($in)"];
        foreach ($this->liensId as $lien) {
            $sauvegardes[$lien['table']] = ($sauvegardes[$lien['table']] ?? null)
                ? $sauvegardes[$lien['table']] . " OR `{$lien['col']}` IN ($in)"
                : "`{$lien['col']}` IN ($in)";
        }
        foreach ($sauvegardes as $t => $where) {
            $bak = "bak_dedup_{$t}_{$suffixe}";
            if (DB::selectOne('SELECT COUNT(*) n FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?', [DB::getDatabaseName(), $bak])->n) {
                $this->error("La table $bak existe déjà : abandon, rien n'a été modifié.");
                return self::FAILURE;
            }
        }
        foreach ($sauvegardes as $t => $where) {
            DB::statement("CREATE TABLE `bak_dedup_{$t}_{$suffixe}` AS SELECT * FROM `$t` WHERE $where");
            $this->line("Sauvegarde : bak_dedup_{$t}_{$suffixe}");
        }

        try {
            DB::transaction(function () use ($groupes, $ambigus) {
                foreach ($this->liensId as $lien) {
                    ['table' => $t, 'col' => $col] = $lien;
                    $cond = $this->conditionDoublon($t, $col);
                    foreach ($groupes as $g) {
                        $inG = implode(',', array_fill(0, count($g['perdants']), '?'));
                        DB::delete(
                            "DELETE l FROM `$t` l WHERE l.`$col` IN ($inG) AND EXISTS (SELECT 1 FROM (SELECT * FROM `$t`) k WHERE k.`$col` = ? AND $cond)",
                            array_merge($g['perdants'], [$g['garde']])
                        );
                        DB::update("UPDATE `$t` SET `$col` = ? WHERE `$col` IN ($inG)", array_merge([$g['garde']], $g['perdants']));
                    }
                }
                foreach ($groupes as $g) {
                    DB::table('e_travailleur')->whereIn('id', $g['perdants'])->delete();
                }
                $restants = DB::table('e_travailleur')->whereNotNull('matricule')->where('matricule', '!=', '')
                    ->groupBy('matricule')->havingRaw('COUNT(*) > 1')->pluck('matricule')
                    ->reject(fn ($m) => isset($ambigus[$m]));
                if ($restants->isNotEmpty()) {
                    throw new \RuntimeException('Doublons restants : ' . $restants->implode(', '));
                }
            });
        } catch (\Throwable $e) {
            $this->error('Échec, transaction annulée : ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Dédoublonnage appliqué. Plus de doublon hors groupes ambigus.');
        return self::SUCCESS;
    }
}
