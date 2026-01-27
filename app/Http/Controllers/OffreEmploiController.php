<?php

namespace App\Http\Controllers;

use App\Departement;
use App\Fonction;
use App\OffreEmploi;
use App\Unites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreEmploiController extends Controller
{
    public function index()
    {
        $offres = OffreEmploi::with(['departement', 'fonction', 'unite'])
            ->orderBy('date_publication', 'desc')
            ->paginate(20);

        return view('recrutement.offres.index', compact('offres'));
    }

    public function create()
    {
        $departements = Departement::orderBy('id', 'DESC')->get();
        $fonctions = Fonction::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();

        return view('recrutement.offres.create', compact('departements', 'fonctions', 'unites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'departement_id' => 'nullable|exists:e_departement,id',
            'fonction_id' => 'nullable|exists:e_fonction,id',
            'unite_id' => 'nullable|exists:e_unites,id',
            'type_contrat' => 'required|in:CDD,CDI,Journalier',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'experience_requise' => 'nullable|string',
            'salaire_min' => 'nullable|numeric|min:0',
            'salaire_max' => 'nullable|numeric|min:0',
            'nombre_postes' => 'required|integer|min:1',
            'date_publication' => 'nullable|date',
            'date_cloture' => 'nullable|date|after_or_equal:date_publication',
        ]);

        $validated['created_by'] = Auth::id();
        if (!$validated['date_publication']) {
            $validated['date_publication'] = now()->toDateString();
        }

        OffreEmploi::create($validated);

        return redirect()->route('offres.index')->with('success', 'Offre d\'emploi créée avec succès.');
    }

    public function edit($id)
    {
        $offre = OffreEmploi::findOrFail($id);
        $departements = Departement::orderBy('id', 'DESC')->get();
        $fonctions = Fonction::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();

        return view('recrutement.offres.edit', compact('offre', 'departements', 'fonctions', 'unites'));
    }

    public function update(Request $request, $id)
    {
        $offre = OffreEmploi::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'departement_id' => 'nullable|exists:e_departement,id',
            'fonction_id' => 'nullable|exists:e_fonction,id',
            'unite_id' => 'nullable|exists:e_unites,id',
            'type_contrat' => 'required|in:CDD,CDI,Journalier',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'experience_requise' => 'nullable|string',
            'salaire_min' => 'nullable|numeric|min:0',
            'salaire_max' => 'nullable|numeric|min:0',
            'nombre_postes' => 'required|integer|min:1',
            'date_cloture' => 'nullable|date',
        ]);

        $offre->update($validated);

        return redirect()->route('offres.index')->with('success', 'Offre d\'emploi mise à jour avec succès.');
    }

    public function close($id)
    {
        $offre = OffreEmploi::findOrFail($id);
        $offre->update(['statut' => 3]); // 3 = Pourvue

        return redirect()->back()->with('success', 'Offre d\'emploi clôturée.');
    }

    public function show($id)
    {
        $offre = OffreEmploi::with(['departement', 'fonction', 'unite', 'candidatures'])->findOrFail($id);
        $stats = [
            'total_candidatures' => $offre->candidatures->count(),
            'preselectionnees' => $offre->candidatures->where('statut_id', 2)->count(),
            'entretiens' => $offre->candidatures->where('statut_id', 3)->count(),
            'offres' => $offre->candidatures->where('statut_id', 4)->count(),
            'embauchees' => $offre->candidatures->where('statut_id', 5)->count(),
            'rejetees' => $offre->candidatures->where('statut_id', 6)->count(),
        ];

        return view('recrutement.offres.show', compact('offre', 'stats'));
    }
}
