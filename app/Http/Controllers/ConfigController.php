<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\NiveauEtude;
use App\Pays;
use App\Santes;
use App\Travailleur;
use App\Unites;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ConfigController extends Controller
{
    public function index(){
        $unites = Unites::orderBy('id', 'DESC')->get();
        $data_departement = Departement::orderBy('id', 'DESC')->get();
        $unitesById = $unites->keyBy('id');
        return view('configuration.departement.add', compact('unites', 'data_departement', 'unitesById'));
    }

    public function adddepartements(Request $get){

        $cmptDepartement = Departement::where('id', $get->id)->first();

        if($cmptDepartement){
            return Redirect::back()->withErrors("Désoler l'identifiant à déjà été utilisé.");
        }else{

            //Envoi des données à la base de données
            $Departements = new Departement();
            $Departements->id = intval($get->id);
            $Departements->label = strtoupper($get->label);
            $Departements->uniteid = $get->uniteid;
            $Departements->description = $get->description;
            $Departements->userid = Auth::user()->id;
            $Departements->created_at = Carbon::now();

            if( $Departements->save() == true){
                return Redirect::back()->withSuccess("Le departement : ".strtoupper($get->label)." a été enregistré avec succès.");
            }
            else{
                return Redirect::back()->withErrors("Errors lors de l'enregistrement, veuillez reprendre le processus.");
            }

        }

    }

    public function editdepartementsurl(Request $get){
        $id = $get->id;
        $data = Departement::find($id);
        return $data;
    }


    public function updatedepartements(Request $get){

        $update = Departement::where("id", $get->id)->update([
            "label" => $get->label,
            "uniteid" => $get->uniteid,
            "description" => $get->description,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deletedepartements(Request $get){
        $id = $get->id;
        $delete = Departement::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function index_unites(){
        $data_unites = Unites::orderBy('id', 'DESC')->get();
        return view('configuration.unites.add', compact('data_unites'));
    }

    public function addunites(Request $get){

        $cmptunites = Unites::where('id', $get->id)->first();

        if($cmptunites){
            return Redirect::back()->withErrors("Désoler l'identifiant à déjà été utilisé.");
        }else{

            //Envoi des données à la base de données
            $Equipe = new Unites();
            $Equipe->id = intval($get->id);
            $Equipe->label = strtoupper($get->label);
            $Equipe->userid = Auth::user()->id;
            $Equipe->created_at = Carbon::now();

            if( $Equipe->save() == true){
                return Redirect::back()->withSuccess("L'unité : ".strtoupper($get->label)." a été enregistré avec succès.");
            }
            else{
                return Redirect::back()->withErrors("Errors lors de l'enregistrement, veuillez reprendre le processus.");
            }

        }

    }

    public function editunitessurl(Request $get){
        $id = $get->id;
        $data = Unites::find($id);
        return $data;
    }

    public function updateunites(Request $get){

        $update = Unites::where("id", $get->id)->update([
            "label" => $get->label,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deleteunites(Request $get){
        $id = $get->id;
        $delete = Unites::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }
        /***** EQUIPE *****/
    public function index_equipes(){

        $termJ = 'J';
        $Travailleur = Travailleur::where('matricule', 'like', '%' . $termJ . '%')->orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();

        $data_equipe = Equipes::orderBy('id', 'DESC')->get();
        return view('configuration.equipe.add2', compact('unites', 'Travailleur', 'data_equipe'));
    }

    public function addequipes(Request $get){

        $cmptDepartement = Equipes::where('id', $get->id)->first();

        if($cmptDepartement){
            return Redirect::back()->withErrors("Désoler l'identifiant à déjà été utilisé.");
        }else{

            //Envoi des données à la base de données
            $Equipe = new Equipes();
            $Equipe->id = intval($get->id);
            $Equipe->label = strtoupper($get->label);
            $Equipe->uniteid = $get->uniteid;
            $Equipe->chefEquipe = $get->chefEquipeid;
            $Equipe->description = $get->description;
            $Equipe->userid = Auth::user()->id;
            $Equipe->created_at = Carbon::now();

            if( $Equipe->save() == true){
                return Redirect::back()->withSuccess("L'equipe : ".strtoupper($get->label)." a été enregistré avec succès.");
            }
            else{
                return Redirect::back()->withErrors("Errors lors de l'enregistrement, veuillez reprendre le processus.");
            }

        }

    }

    public function editequipesurl(Request $get){
        $id = $get->id;
        $data = Equipes::find($id);
        return $data;
    }


    public function updateequipes(Request $get){

        $update = Equipes::where("id", $get->id)->update([
            "label" => $get->label,
            "uniteid" => $get->uniteid,
            "chefEquipe" => $get->chefEquipeid,
            "description" => $get->description,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deleteequipes(Request $get){
        $id = $get->id;
        $delete = Equipes::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    /** FONCTIONS **/

    public function index_fonction(){
        $data_fonction = Fonction::orderBy('id', 'ASC')->get();
        return view('configuration.fonction.add', compact('data_fonction'));
    }

    public function addfonctions(Request $get){

        $cmptFonction = Fonction::where('id', $get->id)->first();

        if($cmptFonction){
            return Redirect::back()->withErrors("Désoler l'identifiant à déjà été utilisé.");
        }else{

                //Envoi des données à la base de données
                $Fonctions = new Fonction();
                $Fonctions->id = intval($get->id);
                $Fonctions->label = strtoupper($get->label);
                $Fonctions->description = $get->description;
                $Fonctions->userid = Auth::user()->id;

                if( $Fonctions->save() == true){
                    return Redirect::back()->withSuccess("La fonction : ".strtolower($get->label)." a été enregistré avec succès.");
                }
                else{
                    return Redirect::back()->withErrors("Errors lors de l'enregistrement, veuillez reprendre le processus.");
                }

        }

    }

    public function editfonctionsurl(Request $get){
        $id = $get->id;
        $data = Fonction::find($id);
        return $data;
    }


    public function updatefonctions(Request $get){

        $update = Fonction::where("id", $get->id)->update([
            "label" => $get->label,
            "description" => $get->description,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deletefonctions(Request $get){
        $id = $get->id;
        $delete = Fonction::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function index_niveauEtude(){
        $data_niveauEtude = NiveauEtude::orderBy('id', 'DESC')->get();
        return view('configuration.niveauEtude.add', compact('data_niveauEtude'));
    }

    public function addniveauEtude(Request $get){
        $insert = NiveauEtude::insert([
            "label" => $get->label,
            "userid" => Auth::user()->id,
            "created_at" => Carbon::now(),
        ]);

        if($insert){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function editniveauEtudeurl(Request $get){
        $id = $get->id;
        $data = NiveauEtude::find($id);
        return $data;
    }


    public function updateniveauEtude(Request $get){

        $update = NiveauEtude::where("id", $get->id)->update([
            "label" => $get->label,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deleteniveauEtude(Request $get){
        $id = $get->id;
        $delete = NiveauEtude::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    /** PAYS **/
    public function index_pays(){
        $data_pays = Pays::orderBy('id', 'DESC')->get();
        return view('configuration.pays.add', compact('data_pays'));
    }

    public function addpays(Request $get){
        $insert = Pays::insert([
            "label" => $get->label,
            "nationalite" => $get->nationalite,
            "userid" => Auth::user()->id,
            "created_at" => Carbon::now(),
        ]);

        if($insert){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function editpaysurl(Request $get){
        $id = $get->id;
        $data = Pays::find($id);
        return $data;
    }


    public function updatepays(Request $get){

        $update = Pays::where("id", $get->id)->update([
            "label" => $get->label,
            "nationalite" => $get->nationalite,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deletepays(Request $get){
        $id = $get->id;
        $delete = Pays::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    /** CATEGORIES **/
    public function index_categories(){
        $data_categories = Categories::orderBy('id', 'DESC')->get();
        return view('configuration.categorie.add', compact('data_categories'));
    }

    public function addcategories(Request $get){
        $insert = Categories::insert([
            "label" => $get->label,
            "userid" => Auth::user()->id,
            "created_at" => Carbon::now(),
        ]);
        if($insert){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function editcategoriesurl(Request $get){
        $id = $get->id;
        $data = Categories::find($id);
        return $data;
    }


    public function updatecategories(Request $get){

        $update = Categories::where("id", $get->id)->update([
            "label" => $get->label,
            "userid" => Auth::user()->id,
            "updated_at" => Carbon::now()
        ]);
        if($update){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

    public function deletecategories(Request $get){
        $id = $get->id;
        $delete = Categories::where("id", $id)->delete();
        if($delete){
            return response()->json("success");
        }else{
            return response()->json("error");
        }
    }

}
