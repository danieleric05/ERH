<?php

namespace App\Http\Controllers;

use App\AutresVariables;
use App\Travailleur;
use App\Services\VariablesPaieAuto;
use App\Support\CatalogueVariablesPaie as Cat;
use App\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class VariablesController extends Controller
{


    public function post_autres_variables(Request $request){

        $verif = AutresVariables::Where('employeid', $request->employeid)->Where('date_variable', $request->date_variable)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible d'avoire des correspondance dans la meme journéee.");
        }else{

            $tabDate = explode("-", $request->date_variable);

            $variables = new AutresVariables();
            $variables->employeid = serialize($request->employeid) ;
            $variables->date_variable = $request->date_variable;
            $variables->cas = $request->cas_variables;
            $variables->montant = $request->montant;
            $variables->statutid = 1; // enregistrer
            $variables->justification = $request->justification;
            $variables->userid = Auth::user()->id;
            $variables->mois = intval($tabDate['1']);
            $variables->annee = intval($tabDate['0']);

            if($variables->save()){
                return Redirect::back()->withSuccess("Variables enregistré avec succès.");
            }
        }


    }

    public function post_variables_manuelle(Request $request){

        $verif = Variables::Where('travailleurid', $request->travailleurid)->Where('type_variable', $request->type_variable)->Where('debut', $request->debut)->Where('fin', $request->fin)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible qu'un journalier ai des variables du meme type dans le meme intervalle.");
        }else{

            $tabDate = explode("-", $request->debut);

            $variables = new Variables();
            $variables->travailleurid = serialize($request->travailleurid) ;
            $variables->type_variable = $request->type_variable;
            $variables->cas_variables = $request->cas_variables;
            $variables->debut = $request->debut;
            $variables->fin = $request->fin;
            $variables->periode = $request->periode;
            $variables->statutid = 1; // enregistrer
            $variables->cause = 1; // manuelle
            $variables->justification = $request->justification;
            $variables->userid = Auth::user()->id;
            $variables->mois = intval($tabDate['1']);
            $variables->annee = intval($tabDate['0']);

            if($variables->save()){
                return Redirect::back()->withSuccess("Variables enregistré avec succès.");
            }
        }


    }

    public function post_variables_heure_sup(Request $request){

        $verif = Variables::Where('employer_hs', $request->employer_hs)->Where('nbre_heure_hs', $request->nbre_heure_hs)->Where('date_hs', $request->date_hs)->first();

        if($verif){
            return Redirect::back()->withErrors("Impossible qu'un employé ai des heures supplémentaire égales durant la meme journée.");
        }else{

            $tabDate = explode("-", $request->date_heure_supp);

            $variables = new Variables();
            $variables->employer_hs = serialize($request->employer_hs) ;
            $variables->nbre_heure_hs = $request->nbre_heure_hs;
            $variables->debut = $request->date_heure_supp;
            $variables->date_hs = $request->date_heure_supp;
            $variables->statutid = 1; // enregistrer
            $variables->cause = 2; // heure suplementaire
            $variables->justification = $request->justification;
            $variables->userid = Auth::user()->id;
            $variables->mois = intval($tabDate['1']);
            $variables->annee = intval($tabDate['0']);

            if($variables->save()){
                return Redirect::back()->withSuccess("Variables, Heures supplémentataire enregistré avec succès.");
            }
        }


    }

    public function listevariables_manuelle(){
        $variableM = Variables::Where('cause', 1)->orderBy('id', 'DESC')->get();
        $matricules = $variableM->flatMap(fn($vari) => $vari->matricules)->unique();
        $travailleursByMatricule = Travailleur::whereIn('matricule', $matricules)->get()->keyBy('matricule');
        return view("variables.listevariables_manuelle", compact('variableM', 'travailleursByMatricule'));
    }

    public function listevariables_heure_supp(Request $request){
        [$annee, $mois] = $this->periodeListe($request, 'hs');
        $lignesPaie = VariablesPaieAuto::lignesDuGroupe('hs', $annee, $mois);
        $variableHS = Variables::Where('cause', 2)->orderBy('id', 'DESC')->get();
        $employes = Travailleur::whereIn('id', $variableHS->flatMap(fn($v) => $v->employes_hs)->unique())->get()->keyBy('id');
        return view("variables.liste_heure_sup", compact('variableHS', 'employes', 'lignesPaie', 'annee', 'mois'));
    }

    /**
     * Variables de présence d'un mois (absences, arrêts maladie, sanctions) : valeurs enregistrées
     * (import ou saisie) ou, à défaut, calculées depuis Santé, Autorisations et Sanctions.
     */
    public function listevariables_automatique(Request $request){
        [$annee, $mois] = $this->periodeListe($request, 'presence');
        $lignesPaie = VariablesPaieAuto::lignesDuGroupe('presence', $annee, $mois);
        return view("variables.listevariables_automatique", compact('lignesPaie', 'annee', 'mois'));
    }

    /** Liste des « autres variables » : primes, rappels, prêt… (données de paie + anciennes saisies). */
    public function listevariables_autres_variables(Request $request){
        [$annee, $mois] = $this->periodeListe($request, 'autres');
        $lignesPaie = VariablesPaieAuto::lignesDuGroupe('autres', $annee, $mois);
        $autres = AutresVariables::orderBy('id', 'DESC')->get();
        $employes = Travailleur::whereIn('id', $autres->flatMap(fn($a) => $a->employes)->unique())->get()->keyBy('id');
        return view('variables.liste_autres_variables', compact('autres', 'employes', 'lignesPaie', 'annee', 'mois'));
    }

    /** Période affichée : celle demandée, sinon le dernier mois qui contient des données du groupe. */
    private function periodeListe(Request $request, string $groupe): array
    {
        if ($request->filled('annee') && $request->filled('mois')) {
            return [(int) $request->query('annee'), max(1, min(12, (int) $request->query('mois')))];
        }

        return VariablesPaieAuto::periodeParDefaut(Cat::codesDuGroupe($groupe));
    }

    /** Écran de modification d'une variable manuelle (pointage) ou d'heures supplémentaires. */
    public function modifier_variable($id){
        $variable = Variables::findOrFail($id);
        $travailleurs = $variable->cause == 2
            ? Travailleur::whereIn('id', $variable->employes_hs)->get()
            : Travailleur::whereIn('matricule', $variable->matricules)->get();
        return view('variables.modifier', compact('variable', 'travailleurs'));
    }

    public function maj_variable(Request $request, $id){
        $variable = Variables::findOrFail($id);

        if ($variable->cause == 2) {
            $data = $request->validate([
                'nbre_heure_hs' => 'required|integer|between:1,24',
                'date_hs' => 'required|date',
                'justification' => 'nullable|string|max:500',
            ]);
            $doublon = Variables::where('cause', 2)->where('id', '!=', $variable->id)->where('employer_hs', $variable->employer_hs)
                ->where('nbre_heure_hs', $data['nbre_heure_hs'])->where('date_hs', $data['date_hs'])->exists();
            if ($doublon) {
                return Redirect::back()->withInput()->withErrors("Ces heures supplémentaires existent déjà pour ces employés à cette date.");
            }
            $variable->nbre_heure_hs = $data['nbre_heure_hs'];
            $variable->date_hs = $data['date_hs'];
            $variable->debut = $data['date_hs'];
            $jour = $data['date_hs'];
        } else {
            $data = $request->validate([
                'type_variable' => 'required|integer|between:1,3',
                'cas_variables' => 'required|integer|between:1,4',
                'debut' => 'required|date',
                'fin' => 'required|date|after_or_equal:debut',
                'periode' => 'nullable|integer|between:1,2',
                'justification' => 'nullable|string|max:500',
            ]);
            $doublon = Variables::where('cause', 1)->where('id', '!=', $variable->id)->where('travailleurid', $variable->travailleurid)
                ->where('type_variable', $data['type_variable'])->where('debut', $data['debut'])->where('fin', $data['fin'])->exists();
            if ($doublon) {
                return Redirect::back()->withInput()->withErrors("Une variable du même type existe déjà pour ces travailleurs sur cette période.");
            }
            foreach (['type_variable', 'cas_variables', 'debut', 'fin', 'periode'] as $champ) {
                $variable->$champ = $data[$champ] ?? null;
            }
            $jour = $data['debut'];
        }

        $variable->justification = $data['justification'] ?? null;
        $variable->statutid = $request->boolean('actif') ? 1 : 2;
        $variable->mois = (int) date('n', strtotime($jour));
        $variable->annee = (int) date('Y', strtotime($jour));
        $variable->save();

        return Redirect::route($variable->cause == 2 ? 'listevariables_heure_supp' : 'listevariables_manuelle')
            ->withSuccess("Variable modifiée avec succès.");
    }

    /** Écran de modification d'une « autre variable ». */
    public function modifier_autre_variable($id){
        $variable = AutresVariables::findOrFail($id);
        $travailleurs = Travailleur::whereIn('id', $variable->employes)->get();
        return view('variables.autres_modifier', compact('variable', 'travailleurs'));
    }

    public function maj_autre_variable(Request $request, $id){
        $variable = AutresVariables::findOrFail($id);
        $data = $request->validate([
            'cas' => 'required|integer|between:1,5',
            'montant' => 'required|integer|min:0',
            'date_variable' => 'required|date',
            'justification' => 'nullable|string|max:500',
        ]);
        $variable->cas = $data['cas'];
        $variable->montant = $data['montant'];
        $variable->date_variable = $data['date_variable'];
        $variable->justification = $data['justification'] ?? null;
        $variable->statutid = $request->boolean('actif') ? 1 : 2;
        $variable->mois = (int) date('n', strtotime($data['date_variable']));
        $variable->annee = (int) date('Y', strtotime($data['date_variable']));
        $variable->save();

        return Redirect::route('listevariables_autres_variables')->withSuccess("Variable modifiée avec succès.");
    }

    /** Annule (désactive) une variable sans la supprimer : statutid 2 = inactif. */
    public function annuler_variable($id){
        $variable = Variables::findOrFail($id);
        $variable->statutid = 2;
        $variable->save();
        return Redirect::back()->withSuccess("Variable annulée.");
    }

    public function annuler_autre_variable($id){
        $variable = AutresVariables::findOrFail($id);
        $variable->statutid = 2;
        $variable->save();
        return Redirect::back()->withSuccess("Variable annulée.");
    }

}
