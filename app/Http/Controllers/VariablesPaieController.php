<?php

namespace App\Http\Controllers;

use App\Services\ImportVariablesPaie;
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
    /** Motifs d'autorisation comptés comme « absence justifiée » (1 = Maladie est déjà dans l'arrêt maladie, 4/5 = congés). */
    private const MOTIFS_ABSENCE_JUSTIFIEE = [2, 3];

    public function index(Request $request)
    {
        [$annee, $mois] = $this->periode($request);
        $recherche = strtoupper(trim((string) $request->query('q', '')));

        [$valeurs, $sources] = $this->valeursDuMois($annee, $mois);

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
        $inconnus = array_sum(array_map(fn ($r) => count($r['matricules_inconnus'] ?? []), $rapport));
        $mois = count(array_filter($rapport, fn ($r) => !isset($r['ignoree'])));
        $msg = ($simulation ? 'SIMULATION (rien enregistré) : ' : 'Import terminé : ') . "$valeurs valeurs sur $mois mois, $inconnus matricules absents de la base.";

        return Redirect::back()->withSuccess($msg);
    }

    public function export(Request $request)
    {
        [$annee, $mois] = $this->periode($request);
        [$valeurs] = $this->valeursDuMois($annee, $mois);
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

    /**
     * @return array{0: array<string, array<string, float>>, 1: array<string, array<string, string>>}
     *         valeurs[matricule][code] et sources[matricule][code] ('enregistre' | 'auto')
     */
    private function valeursDuMois(int $annee, int $mois): array
    {
        $valeurs = [];
        $sources = [];

        foreach ($this->calculAutomatique($annee, $mois) as $matricule => $codes) {
            foreach ($codes as $code => $v) {
                $valeurs[$matricule][$code] = $v;
                $sources[$matricule][$code] = 'auto';
            }
        }

        $enregistrees = VariablePaie::where('annee', $annee)->where('mois', $mois)
            ->whereIn('code', Cat::visibles())->get(['matricule', 'code', 'valeur']);
        $sommes = [];
        foreach ($enregistrees as $e) {
            $sommes[$e->matricule][$e->code] = ($sommes[$e->matricule][$e->code] ?? 0) + $e->valeur;
        }
        foreach ($sommes as $matricule => $codes) {
            foreach ($codes as $code => $v) {
                $valeurs[$matricule][$code] = $v;          // l'enregistré l'emporte sur le calcul
                $sources[$matricule][$code] = 'enregistre';
            }
        }

        return [$valeurs, $sources];
    }

    /** Jours d'arrêt maladie, d'absence justifiée et de sanction du mois, calculés depuis les autres modules. */
    private function calculAutomatique(int $annee, int $mois): array
    {
        $debut = Carbon::create($annee, $mois, 1)->startOfDay();
        $fin = $debut->copy()->endOfMonth()->startOfDay();
        $out = [];

        $jours = function ($d, $f) use ($debut, $fin) {
            $d = Carbon::parse($d)->startOfDay();
            $f = Carbon::parse($f)->startOfDay();
            $a = $d->gt($debut) ? $d : $debut;
            $b = $f->lt($fin) ? $f : $fin;

            return $b->gte($a) ? $a->diffInDays($b) + 1 : 0;
        };

        // Arrêt maladie : consultations avec arrêt de travail chevauchant le mois
        $maladies = DB::table('e_consultation')->where('statutid', 1)->where('arret_travail', 1)
            ->whereNotNull('debut_arret')->whereNotNull('fin_arret')
            ->where('debut_arret', '<=', $fin->toDateString())->where('fin_arret', '>=', $debut->toDateString())
            ->get(['travailleur_mat', 'debut_arret', 'fin_arret']);
        foreach ($maladies as $m) {
            if ($m->travailleur_mat && ($j = $jours($m->debut_arret, $m->fin_arret))) {
                $out[$m->travailleur_mat]['ARRET_MALADIE'] = ($out[$m->travailleur_mat]['ARRET_MALADIE'] ?? 0) + $j;
            }
        }

        // Absence justifiée : autorisations de type convenance personnelle / permission exceptionnelle
        $autorisations = DB::table('e_autorisation as a')->join('e_travailleur as t', 't.id', '=', 'a.demandeurid')
            ->where('a.statutid', 1)->whereIn('a.motif_absence', self::MOTIFS_ABSENCE_JUSTIFIEE)
            ->whereNotNull('a.debut')->whereNotNull('a.fin')
            ->where('a.debut', '<=', $fin->toDateString())->where('a.fin', '>=', $debut->toDateString())
            ->get(['t.matricule', 'a.debut', 'a.fin']);
        foreach ($autorisations as $a) {
            if ($j = $jours($a->debut, $a->fin)) {
                $out[$a->matricule]['ABSENCE_JUSTIFIEE'] = ($out[$a->matricule]['ABSENCE_JUSTIFIEE'] ?? 0) + $j;
            }
        }

        // Sanction disciplinaire : nombre de jours de mise à pied dont le début tombe dans le mois
        $sanctions = DB::table('e_sanction')->where('statutid', 1)->where('nombre_jour', '>', 0)
            ->whereBetween('debut', [$debut->toDateString(), $fin->toDateString()])
            ->get(['employeid', 'nombre_jour']);
        foreach ($sanctions as $s) {
            $liste = @unserialize($s->employeid);
            foreach (is_array($liste) ? $liste : [] as $matricule) {
                $out[$matricule]['SANCTION'] = ($out[$matricule]['SANCTION'] ?? 0) + $s->nombre_jour;
            }
        }

        return $out;
    }
}
