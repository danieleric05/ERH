<?php

namespace App\Console\Commands;

use App\Categories;
use App\Departement;
use App\Fonction;
use App\Travailleur;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOdooPersonnel extends Command
{
    protected $signature = 'erh:sync-odoo-personnel
        {--file= : Chemin du CSV export Odoo (colonnes: Référence employé, Département, Poste, Catégorie d\'employé, Type de contrat, ...)}
        {--apply : Écrit réellement les changements (par défaut : rapport à sec, lecture seule)}';

    protected $description = "Synchronise idtype_contrat, departementid, fonction_entrepriseid et categorieid des travailleurs ERH à partir de l'export Odoo (référence). Ne devine jamais un mapping texte→ID approximatif : toute valeur Odoo sans correspondance exacte est listée séparément, pas appliquée. Liste aussi (sans agir) les employés présents uniquement dans Odoo et les travailleurs ERH actifs absents d'Odoo.";

    private const TYPE_CONTRAT_MAP = [
        'STAGE' => 1,
        'CDD' => 2,
        'CDI' => 3,
        'JOURNALIER' => 4,
        'CDDJ' => 4,
        'STAGE ECOLE' => 5,
        'STAGE DE QUALIFICATION' => 6,
    ];

    public function handle(): int
    {
        $apply = $this->option('apply');
        $file = $this->option('file') ?: storage_path('app/odoo/personnel_odoo.csv');

        if (!is_readable($file)) {
            $this->error("Fichier introuvable ou illisible : {$file}");
            return self::FAILURE;
        }

        // --- 1. Table de correspondance texte Odoo -> ID ERH (exact match sur libellé normalisé) ---
        $depIndex = $this->buildIndex(Departement::pluck('label', 'id'));
        $fonIndex = $this->buildIndex(Fonction::pluck('label', 'id'));
        $catIndex = $this->buildIndex(Categories::pluck('label', 'id'));

        // --- 2. Parse le CSV Odoo ---
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        $odoo = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($header)) {
                continue;
            }
            $rec = array_combine($header, $row);
            $mat = strtoupper(trim($rec['Référence employé']));
            if ($mat === '') {
                continue;
            }
            $odoo[$mat] = $rec;
        }
        fclose($handle);

        $this->info('Odoo : ' . count($odoo) . ' matricules uniques');

        // --- 3. Charge les travailleurs ERH correspondants ---
        $erhByMat = [];
        foreach (array_chunk(array_keys($odoo), 500) as $chunk) {
            foreach (Travailleur::whereIn('matricule', $chunk)->get() as $t) {
                $erhByMat[strtoupper($t->matricule)] = $t;
            }
        }
        $this->info('Correspondances par matricule (Odoo ∩ ERH) : ' . count($erhByMat));

        // --- 4. Odoo-only (118 attendus) ---
        $missingInErh = array_diff(array_keys($odoo), array_keys($erhByMat));
        $this->newLine();
        $this->info('📋 Employés Odoo ABSENTS d\'ERH (' . count($missingInErh) . ') — listés, PAS créés automatiquement :');
        foreach ($missingInErh as $mat) {
            $o = $odoo[$mat];
            $this->line("  {$mat}  {$o["Nom de l'employé"]}  |  {$o['Type de contrat']}  |  {$o['Département']}  |  {$o['Poste']}");
        }

        // --- 5. ERH-actifs-only (280 attendus) ---
        $erhActifs = Travailleur::actif()->get();
        $missingInOdoo = $erhActifs->filter(function ($t) use ($odoo) {
            $mat = strtoupper($t->matricule ?? '');
            return $mat !== '' && !isset($odoo[$mat]);
        });
        $this->newLine();
        $this->info('📋 Travailleurs ERH actifs ABSENTS d\'Odoo (' . $missingInOdoo->count() . ') — listés, PAS cessés automatiquement :');
        foreach ($missingInOdoo as $t) {
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  etapeid={$t->etapeid}");
        }

        // --- 6. Écarts pour les matricules communs ---
        $updates = [];
        $unmappedDep = [];
        $unmappedFon = [];
        $unmappedCat = [];
        $unmappedType = [];

        foreach ($erhByMat as $mat => $t) {
            $o = $odoo[$mat];
            $diff = [];

            $odooType = strtoupper(trim($o['Type de contrat']));
            if ($odooType !== '') {
                if (isset(self::TYPE_CONTRAT_MAP[$odooType])) {
                    $newType = self::TYPE_CONTRAT_MAP[$odooType];
                    if ($newType != $t->idtype_contrat) {
                        $diff['idtype_contrat'] = [$t->idtype_contrat, $newType];
                    }
                } else {
                    $unmappedType[$odooType] = ($unmappedType[$odooType] ?? 0) + 1;
                }
            }

            $odooDep = $this->normalize($o['Département']);
            if ($odooDep !== '') {
                if (isset($depIndex[$odooDep])) {
                    $newDep = $depIndex[$odooDep];
                    if ($newDep != $t->departementid) {
                        $diff['departementid'] = [$t->departementid, $newDep];
                    }
                } else {
                    $unmappedDep[$odooDep] = ($unmappedDep[$odooDep] ?? 0) + 1;
                }
            }

            $odooFon = $this->normalize($o['Poste']);
            if ($odooFon !== '') {
                if (isset($fonIndex[$odooFon])) {
                    $newFon = $fonIndex[$odooFon];
                    if ($newFon != $t->fonction_entrepriseid) {
                        $diff['fonction_entrepriseid'] = [$t->fonction_entrepriseid, $newFon];
                    }
                } else {
                    $unmappedFon[$odooFon] = ($unmappedFon[$odooFon] ?? 0) + 1;
                }
            }

            $odooCat = $this->normalize($o["Catégorie d'employé"]);
            if ($odooCat !== '') {
                if (isset($catIndex[$odooCat])) {
                    $newCat = $catIndex[$odooCat];
                    if ($newCat != $t->categorieid) {
                        $diff['categorieid'] = [$t->categorieid, $newCat];
                    }
                } else {
                    $unmappedCat[$odooCat] = ($unmappedCat[$odooCat] ?? 0) + 1;
                }
            }

            if (!empty($diff)) {
                $updates[] = ['t' => $t, 'diff' => $diff];
            }
        }

        $this->newLine();
        $this->info('🔁 Travailleurs avec au moins un écart à corriger : ' . count($updates));
        foreach ($updates as $u) {
            $t = $u['t'];
            $parts = [];
            foreach ($u['diff'] as $field => [$old, $new]) {
                $parts[] = "{$field}: " . ($old ?? 'NULL') . " → {$new}";
            }
            $this->line("  {$t->matricule}  {$t->nom} {$t->prenom}  |  " . implode(' ; ', $parts));
        }

        $this->reportUnmapped('Départements Odoo sans correspondance ERH', $unmappedDep);
        $this->reportUnmapped('Postes Odoo sans correspondance ERH', $unmappedFon);
        $this->reportUnmapped('Catégories Odoo sans correspondance ERH', $unmappedCat);
        $this->reportUnmapped('Types de contrat Odoo non reconnus', $unmappedType);

        if (!$apply) {
            $this->newLine();
            $this->info('Rapport à sec (--apply non passé) : rien n\'a été écrit.');
            return self::SUCCESS;
        }

        $this->newLine();
        if (!$this->confirm('Appliquer réellement ces ' . count($updates) . ' correction(s) de travailleurs existants ? (aucune création/cessation automatique)')) {
            $this->info('Annulé.');
            return self::SUCCESS;
        }

        foreach ($updates as $u) {
            $t = $u['t'];
            foreach ($u['diff'] as $field => [$old, $new]) {
                $t->{$field} = $new;
            }
            $t->save();
            Log::info("Sync Odoo: travailleur {$t->id} ({$t->matricule}) mis à jour : " . json_encode($u['diff']));
        }

        $this->info('✅ ' . count($updates) . ' travailleur(s) mis à jour.');

        return self::SUCCESS;
    }

    private function normalize(string $s): string
    {
        $s = trim($s);
        $s = mb_strtoupper($s, 'UTF-8');
        $s = preg_replace('/\s+/', ' ', $s);
        return $s;
    }

    /**
     * Construit l'index libellé normalisé -> ID, en excluant les libellés dupliqués
     * (plusieurs lignes avec le même texte mais un ID différent) : impossible de
     * savoir lequel est le "bon" sans deviner, donc on ne mappe pas ces cas-là.
     */
    private function buildIndex($labels): array
    {
        $byLabel = [];
        foreach ($labels as $id => $label) {
            $byLabel[$this->normalize($label)][] = $id;
        }
        $idx = [];
        foreach ($byLabel as $label => $ids) {
            if (count($ids) === 1) {
                $idx[$label] = $ids[0];
            }
        }
        return $idx;
    }

    private function reportUnmapped(string $title, array $counts): void
    {
        if (empty($counts)) {
            return;
        }
        arsort($counts);
        $this->newLine();
        $this->warn("⚠️  {$title} (" . count($counts) . ' valeurs distinctes, non appliquées) :');
        foreach ($counts as $label => $count) {
            $this->line("  {$label}: {$count}");
        }
    }
}
