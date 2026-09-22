<?php

namespace App\Http\Controllers;

use App\Services\ImportRepertoireContrats;
use App\Services\ModeleImportContrats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportContratsController extends Controller
{
    public function index()
    {
        return view('import_contrats.index', ['rapport' => null, 'simulation' => true, 'csv' => null]);
    }

    public function importer(Request $request, ImportRepertoireContrats $service)
    {
        $request->validate(['fichier' => 'required|file|mimes:xlsx|max:20480']);
        $simulation = $request->boolean('simulation');

        @set_time_limit(300);
        @ini_set('memory_limit', '1G');
        try {
            $rapport = $service->importer($request->file('fichier')->getRealPath(), $simulation, Auth::id());
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }
        $csv = $this->ecrireCsv($rapport, $simulation);

        return view('import_contrats.index', ['rapport' => $rapport, 'simulation' => $simulation, 'csv' => basename($csv)]);
    }

    public function modele()
    {
        $classeur = (new ModeleImportContrats())->construire();

        return response()->streamDownload(fn () => (new Xlsx($classeur))->save('php://output'), 'modele_import_travailleurs_contrats.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function rapport(string $fichier)
    {
        $chemin = storage_path('app/imports_contrats/' . basename($fichier));
        abort_unless(preg_match('/^rapport_[0-9_]+(_simulation)?\.csv$/', basename($fichier)) && is_file($chemin), 404);

        return response()->download($chemin);
    }

    private function ecrireCsv(array $rapport, bool $simulation): string
    {
        $dir = storage_path('app/imports_contrats');
        @mkdir($dir, 0775, true);
        $chemin = $dir . '/rapport_' . date('Ymd_His') . ($simulation ? '_simulation' : '') . '.csv';
        $h = fopen($chemin, 'w');
        fwrite($h, "\xEF\xBB\xBF");
        fputcsv($h, ['ligne', 'statut', 'matricule', 'nom', 'changements', 'avertissements', 'erreur'], ';');
        foreach ($rapport['lignes'] as $l) {
            fputcsv($h, [$l['ligne'], $l['statut'], $l['matricule'], $l['nom'], implode(' | ', $l['changements']), implode(' | ', $l['avertissements']), $l['erreur']], ';');
        }
        fclose($h);

        return $chemin;
    }
}
