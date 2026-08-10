<?php

namespace App\Http\Controllers;

use App\AutresVariables;
use App\Travailleur;
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
        $matricules = $variableM->flatMap(fn($vari) => unserialize($vari->travailleurid))->unique();
        $travailleursByMatricule = Travailleur::whereIn('matricule', $matricules)->get()->keyBy('matricule');
        return view("variables.listevariables_manuelle", compact('variableM', 'travailleursByMatricule'));
    }

    public function listevariables_heure_supp(){
        $variableHS = Variables::Where('cause', 2)->orderBy('id', 'DESC')->get();
        $ids = $variableHS->flatMap(fn($vari) => unserialize($vari->employer_hs))->unique();
        $travailleursById = Travailleur::whereIn('id', $ids)->get()->keyBy('id');
        return view("variables.liste_heure_sup", compact('variableHS', 'travailleursById'));
    }

    public function listevariables_automatique(){
        $variableHS = Variables::Where('cause', 2)->orderBy('id', 'DESC')->get();
        return view("variables.listevariables_automatique", compact('variableHS'));
    }

    public function listevariables_autres_variables(){
        $data_travailleur = Travailleur::where('etapeid', '!=', 3)->where('statutid', '!=', 4)->orderBy('id', 'DESC')->get();
        return view('variables.autres_variables', compact('data_travailleur'));
    }


}
