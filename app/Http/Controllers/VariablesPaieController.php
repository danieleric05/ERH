<?php

namespace App\Http\Controllers;

use App\Services\ImportVariablesPaie;
use App\Services\VariablesPaieAuto;
use App\Support\CatalogueVariablesPaie as Cat;
use App\Travailleur;
use App\VariablePaie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Variables de paie mensuelles : remplace le fichier Excel « VARIABLES ».
 *
 * Valeur affichée d'une variable = valeur enregistrée (import ou saisie) si elle
 * existe, sinon valeur calculée depuis les autres modules (santé, sanctions,
 * autorisations). Une valeur enregistrée l'emporte donc toujours sur le calcul.
 */
class VariablesPaieController extends Controller
{
    public function index(Request $request)
    {
        [$annee, $mois] = $this->periode($request);
        $recherche = strtoupper(trim((string) $request->query('q', '')));

        [$valeurs, $sources] = VariablesPaieAuto::valeursDuMois($annee, $mois);

        $matricules = array_keys($valeurs);
        $travailleurs = Travailleur::whereIn('matricule', $matricules)->get(['id', 'matricule', 'nom', 'prenom', 'prenom_suite'])->keyBy('matricule');

        $lignes = [];
        foreach ($valeurs as $matricule => $codes) {
            $t = $travailleurs[$matricule] ?? null;
            $nom = $t ? trim($t->nom . ' ' . $t->prenoms_complets) : '(matricule inconnu)';
            if ($recherche !== '' && !str_contains(strtoupper($matricule . ' ' . $nom), $recherche)) {
                continue;
            }
            $lignes[] = ['matricule' => $matricule, 'nom' => $nom, 'connu' => (bool) $t, 'valeurs' => $codes, 'sources' => $sources[$matricule] ?? []];
        }
        usort($lignes, fn ($a, $b) => strcmp($a['matricule'], $b['matricule']));

        $codes = array_intersect_key(Cat::CODES, array_flip(Cat::visibles()));
        $totaux = [];
        foreach ($lignes as $l) {
            foreach ($l['valeurs'] as $code => $v) {
                $totaux[$code] = ($totaux[$code] ?? 0) + $v;
            }
        }

        $codesGrille = array_filter($codes, fn ($def, $code) => !empty($totaux[$code]), ARRAY_FILTER_USE_BOTH);

        return view('variables.paie', compact('annee', 'mois', 'recherche', 'lignes', 'codes', 'codesGrille', 'totaux'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|string|max:20',
            'annee' => 'required|integer|between:2019,2100',
            'mois' => 'required|integer|between:1,12',
            'code' => 'required|string',
            'semaine' => 'nullable|integer|between:0,' . Cat::NB_SEMAINES,
            'valeur' => 'required|numeric|min:0',
        ]);

        $code = $data['code'];
        if (!in_array($code, Cat::visibles(), true)) {
            return Redirect::back()->withErrors('Variable inconnue.');
        }
        $matricule = strtoupper(trim($data['matricule']));
        $travailleur = Travailleur::where('matricule', $matricule)->first();
        if (!$travailleur) {
            return Redirect::back()->withErrors("Matricule $matricule introuvable.")->withInput();
        }

        $hebdo = !empty(Cat::CODES[$code]['hebdo']);
        $semaine = $hebdo ? (int) ($data['semaine'] ?? 1) : 0;
        if ($hebdo && $semaine < 1) {
            $semaine = 1;
        }
        $cle = ['matricule' => $matricule, 'annee' => $data['annee'], 'mois' => $data['mois'], 'code' => $code, 'semaine' => $semaine];

        VariablePaie::updateOrCreate($cle, [
            'travailleurid' => $travailleur->id,
            'valeur' => $data['valeur'],
            'source' => 'manuel',
            'userid' => Auth::id(),
        ]);

        return Redirect::route('variables_paie', ['annee' => $data['annee'], 'mois' => $data['mois']])
            ->withSuccess(Cat::libelle($code) . " enregistré pour $matricule.");
    }

    /** Écran de modification d'une variable pour un travailleur et un mois (une ligne par semaine si hebdomadaire). */
    public function edit(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|string|max:20',
            'annee' => 'required|integer|between:2019,2100',
            'mois' => 'required|integer|between:1,12',
            'code' => 'required|string',
        ]);
        $code = $data['code'];
        if (!in_array($code, Cat::visibles(), true)) {
            abort(404);
        }
        $matricule = strtoupper(trim($data['matricule']));
        $def = Cat::CODES[$code];

        $enregistrees = VariablePaie::where('matricule', $matricule)->where('annee', $data['annee'])
            ->where('mois', $data['mois'])->where('code', $code)->orderBy('semaine')->get()->keyBy('semaine');
        $calcul = VariablesPaieAuto::pourMois((int) $data['annee'], (int) $data['mois'])[$matricule][$code] ?? null;
        $travailleur = Travailleur::where('matricule', $matricule)->first();

        return view('variables.paie_modifier', [
            'matricule' => $matricule, 'annee' => (int) $data['annee'], 'mois' => (int) $data['mois'],
            'code' => $code, 'def' => $def, 'hebdo' => Cat::estHebdo($code),
            'nbSemaines' => Cat::NB_SEMAINES, 'enregistrees' => $enregistrees, 'calcul' => $calcul, 'travailleur' => $travailleur,
        ]);
    }

    /** Enregistre toutes les semaines du formulaire : une case vide supprime la valeur enregistrée. */
    public function update(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|string|max:20',
            'annee' => 'required|integer|between:2019,2100',
            'mois' => 'required|integer|between:1,12',
            'code' => 'required|string',
            'valeurs' => 'required|array',
            'valeurs.*' => 'nullable|numeric|min:0',
        ]);
        $code = $data['code'];
        if (!in_array($code, Cat::visibles(), true)) {
            return Redirect::back()->withErrors('Variable inconnue.');
        }
        $matricule = strtoupper(trim($data['matricule']));
        $travailleur = Travailleur::where('matricule', $matricule)->first();
        $hebdo = Cat::estHebdo($code);
        $cle = ['matricule' => $matricule, 'annee' => $data['annee'], 'mois' => $data['mois'], 'code' => $code];

        foreach ($data['valeurs'] as $semaine => $valeur) {
            $semaine = $hebdo ? (int) $semaine : 0;
            if ($hebdo && ($semaine < 1 || $semaine > Cat::NB_SEMAINES)) {
                continue;
            }
            $ligne = VariablePaie::where($cle)->where('semaine', $semaine);
            if ($valeur === null || $valeur === '') {
                $ligne->delete();
                continue;
            }
            $existante = $ligne->first();
            if ($existante && (float) $existante->valeur === (float) $valeur) {
                continue;   // inchangée : on garde la source d'origine (import)
            }
            VariablePaie::updateOrCreate($cle + ['semaine' => $semaine], [
                'travailleurid' => $travailleur->id ?? null,
                'valeur' => $valeur,
                'source' => 'manuel',
                'userid' => Auth::id(),
            ]);
        }

        return Redirect::route('variables_paie', ['annee' => $data['annee'], 'mois' => $data['mois']])
            ->withSuccess(Cat::libelle($code) . " mis à jour pour $matricule.");
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|string|max:20',
            'annee' => 'required|integer',
            'mois' => 'required|integer|between:1,12',
            'code' => 'required|string',
        ]);

        VariablePaie::where('matricule', $data['matricule'])->where('annee', $data['annee'])
            ->where('mois', $data['mois'])->where('code', $data['code'])->delete();

        return Redirect::route('variables_paie', ['annee' => $data['annee'], 'mois' => $data['mois']])
            ->withSuccess('Valeur supprimée.');
    }

    public function import(Request $request, ImportVariablesPaie $service)
    {
        $request->validate(['fichier' => 'required|file|mimes:xlsx|max:20480']);
        $simulation = $request->boolean('simulation');

        @set_time_limit(300);
        @ini_set('memory_limit', '2G');
        $rapport = $service->importer($request->file('fichier')->getRealPath(), $simulation, null, Auth::id());

        $valeurs = array_sum(array_column($rapport, 'cellules'));
        $inconnus = count(array_unique(array_merge([], ...array_values(array_map(fn ($r) => $r["matricules_inconnus"] ?? [], $rapport)))));
        $mois = count(array_filter($rapport, fn ($r) => !isset($r['ignoree'])));
        $msg = ($simulation ? 'SIMULATION (rien enregistré) : ' : 'Import terminé : ') . "$valeurs valeurs sur $mois mois, $inconnus matricules absents de la base.";

        return Redirect::back()->withSuccess($msg);
    }

    public function export(Request $request)
    {
        [$annee, $mois] = $this->periode($request);
        [$valeurs] = VariablesPaieAuto::valeursDuMois($annee, $mois);
        $travailleurs = Travailleur::whereIn('matricule', array_keys($valeurs))->get()->keyBy('matricule');
        $codes = array_intersect_key(Cat::CODES, array_flip(Cat::visibles()));

        $classeur = new Spreadsheet();
        $ws = $classeur->getActiveSheet();
        $ws->setTitle(sprintf('%02d-%d', $mois, $annee));
        $ws->fromArray(array_merge(['Matricule', 'Nom', 'Prénoms'], array_map(fn ($c) => $c['libelle'] . ' (' . $c['unite'] . ')', array_values($codes))), null, 'A1');

        $r = 2;
        ksort($valeurs);
        foreach ($valeurs as $matricule => $v) {
            $t = $travailleurs[$matricule] ?? null;
            $ligne = [$matricule, $t->nom ?? '', $t->prenoms_complets ?? ''];
            foreach (array_keys($codes) as $code) {
                $ligne[] = $v[$code] ?? null;
            }
            $ws->fromArray($ligne, null, 'A' . $r++);
        }
        $ws->getStyle('A1:' . $ws->getHighestColumn() . '1')->getFont()->setBold(true);
        foreach (range('A', $ws->getHighestColumn()) as $col) {
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        $nom = sprintf('variables_paie_%d_%02d.xlsx', $annee, $mois);

        return response()->streamDownload(function () use ($classeur) {
            (new Xlsx($classeur))->save('php://output');
        }, $nom, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function periode(Request $request): array
    {
        $annee = (int) $request->query('annee', date('Y'));
        $mois = (int) $request->query('mois', date('n'));

        return [$annee, max(1, min(12, $mois))];
    }
}
