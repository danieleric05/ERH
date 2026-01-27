<?php

namespace App\Http\Controllers;

use App\Candidat;
use App\Candidature;
use App\OffreEmploi;
use App\StatutCandidature;
use App\Travailleur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $candidatures = Candidature::with(['candidat', 'offre', 'statut'])
            ->orderBy('date_candidature', 'desc');

        // Filtrer par offre si spécifié
        if ($request->has('offre') && $request->offre) {
            $candidatures->where('offre_emploi_id', $request->offre);
        }

        // Filtrer par statut si spécifié
        if ($request->has('statut') && $request->statut) {
            $candidatures->where('statut_id', $request->statut);
        }

        $candidatures = $candidatures->paginate(20);
        $offres = OffreEmploi::orderBy('titre')->get();
        $statuts = StatutCandidature::orderBy('ordre')->get();

        return view('recrutement.candidatures.index', compact('candidatures', 'offres', 'statuts'));
    }

    public function kanban(Request $request)
    {
        $statuts = StatutCandidature::orderBy('ordre')->get();

        // Charger les candidatures groupées par statut
        $candidatures = Candidature::with(['candidat', 'offre'])
            ->get()
            ->groupBy('statut_id');

        // Filtrer par offre si spécifié
        if ($request->has('offre') && $request->offre) {
            $candidatures = Candidature::where('offre_emploi_id', $request->offre)
                ->with(['candidat', 'offre'])
                ->get()
                ->groupBy('statut_id');
        }

        $offres = OffreEmploi::where('statut', 1)->orderBy('titre')->get();

        return view('recrutement.candidatures.kanban', compact('statuts', 'candidatures', 'offres'));
    }

    public function show($id)
    {
        $candidature = Candidature::with(['candidat', 'offre', 'statut', 'entretiens', 'evaluations'])->findOrFail($id);
        $statuts = StatutCandidature::orderBy('ordre')->get();
        $users = \App\User::where('statut_id', 1)->orderBy('pseudo')->get();

        return view('recrutement.candidatures.show', compact('candidature', 'statuts', 'users'));
    }

    public function changerStatut(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $request->validate([
            'statut_id' => 'required|exists:e_statut_candidature,id',
        ]);

        $candidature->update([
            'statut_id' => $request->statut_id,
            'date_changement_statut' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Statut de candidature mis à jour.');
    }

    public function assignerRecruteur(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $request->validate([
            'assignee_a' => 'nullable|exists:user,id',
        ]);

        $candidature->update(['assignee_a' => $request->assignee_a]);

        return redirect()->back()->with('success', 'Recruteur assigné avec succès.');
    }

    public function rejeter(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $request->validate([
            'raison' => 'required|string|max:500',
        ]);

        $candidature->update([
            'statut_id' => 6, // Rejeté
            'rejetee_raison' => $request->raison,
            'date_changement_statut' => Carbon::now(),
        ]);

        // TODO: Envoyer email au candidat

        return redirect()->back()->with('success', 'Candidature rejetée.');
    }

    public function convertirEnTravailleur(Request $request, $id)
    {
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);

        // Vérifier que le candidat est en statut "Embauché" ou "Offre acceptée"
        if (!in_array($candidature->statut_id, [4, 5])) {
            return redirect()->back()->withErrors('Le candidat doit être en statut "Offre" ou "Embauché".');
        }

        // Créer l'entrée travailleur
        $travailleur = Travailleur::create([
            'nom' => $candidature->candidat->nom,
            'prenom' => $candidature->candidat->prenom,
            'civilite' => $candidature->candidat->civilite,
            'date_naissance' => $candidature->candidat->date_naissance,
            'telephone' => $candidature->candidat->telephone,
            'email' => $candidature->candidat->email,
            'adresse' => $candidature->candidat->adresse,
            'niveau_etude_id' => $candidature->candidat->niveau_etude_id,
            'departement_id' => $candidature->offre->departement_id,
            'fonction_id' => $candidature->offre->fonction_id,
            'unite_id' => $candidature->offre->unite_id,
            'type_contrat' => $candidature->offre->type_contrat,
            'date_debut_contrat' => now()->toDateString(),
            'etapeid' => 1, // Étape 2 (validation)
        ]);

        // Copier la photo si disponible
        if ($candidature->candidat->photo_path) {
            $sourcePath = 'rhassets/images/candidats/' . $candidature->candidat->photo_path;
            $destPath = 'rhassets/images/travailleurs/' . time() . '_' . $travailleur->matricule . '.jpg';
            if (file_exists(public_path('../' . $sourcePath))) {
                copy(public_path('../' . $sourcePath), public_path('../' . $destPath));
                $travailleur->update(['photo' => basename($destPath)]);
            }
        }

        // Marquer la candidature comme embauchée
        $candidature->update([
            'statut_id' => 5, // Embauché
            'date_changement_statut' => Carbon::now(),
        ]);

        return redirect()->route('etapedeuxtravailleur', $travailleur->id)
            ->with('success', 'Candidat converti en travailleur. Complétez les informations.');
    }

    public function evaluer($id)
    {
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);

        return view('recrutement.candidatures.evaluer', compact('candidature'));
    }

    public function storeEvaluation(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $candidature->update([
            'score_evaluation' => $request->score,
            'notes' => $request->notes,
        ]);

        return redirect()->route('candidatures.show', $candidature->id)
            ->with('success', 'Candidat évalué avec succès.');
    }
}
