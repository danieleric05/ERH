<?php

namespace App\Http\Controllers;

use App\ActionsCDC;
use App\Categories;
use App\Commune;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\NiveauEtude;
use App\Pays;
use App\Santes;
use App\Tenues;
use App\Travailleur;
use App\Unites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Types\Null_;
use PDF;

class EmployerController extends Controller
{

    public function post_gestion_tenue(Request $request)
    {
        $verif_mat = Tenues::where('travailleurid', strtoupper($request->travailleurid))->where('etat', 1)->first();
        if($verif_mat){
            return Redirect::back()->withErrors("Désoler ce matricule a déja une tenue en bonne etat, veuillez changer l'etat de la tenue svp.");
        }else{

            $tenue = new Tenues();
            $tenue->travailleurid = $request->travailleurid;
            $tenue->datereception = $request->datereception;
            $tenue->tenuerecu = $request->tenuerecu;
            $tenue->detail_tenue = $request->detail_tenue;
            $tenue->detail_chaussure = $request->detail_chaussure;
            $tenue->etat = 1; //1 bon etat 2 mauvais etat
            $tenue->userid = Auth::user()->id;
            $tenue->mois = date('m');
            $tenue->annee = date('Y');
            $tenue->save();
            if($tenue->save()){
                return Redirect::back()->withSuccess("La tenue du travailleur a été enregistré avec succès.");
            }
        }

    }


    public function listetenues(){

        $tenues = Tenues::orderBy('id', 'DESC')->get();

        return view("tenues.liste", compact('departements', 'tenues'));

    }

    public function post_travailleur(Request $request)
    {

        $verif_mat = Travailleur::where('matricule', strtoupper($request->matricule))->first();

        if($verif_mat){
            return Redirect::back()->withErrors("Désoler ce matricule a été deja utilisé.");
        }else{

            $datefincontrat = date( "Y-m-d", strtotime( "$request->dateembauche +330 day" ) );
            $tabDate = explode("-", $request->dateembauche);
            $travail = new Travailleur();
            $travail->matricule = $request->matricule;
            $travail->civilite = NULL;
            $travail->nom = $request->nom;
            $travail->prenom = $request->prenom;
            $travail->date_naissance = $request->datenaissance;
            $travail->numero_securite = Null;
            $travail->date_fin_contrat = $datefincontrat;
            $travail->date_debut_contrat = $request->dateembauche;
            $travail->situation_mat = $request->situation_mat;
            $travail->nombre_enfant = null;
            $travail->uniteid = $request->uniteid;
            $travail->categorieid = null;
            $travail->fonction_entrepriseid = null;
            $travail->bulletin_modele_salarie = null;
            $travail->type_salaire = null;
            $travail->email = null;
            $travail->statutid = 1; // enregistrer etape 1
            $travail->userid = Auth::user()->id; // enregistrer par
            $travail->type_employer = $request->typeEmployer; //
            $travail->departementid = $request->departementid;
            $travail->equipeid = $request->equipeid;
            $travail->nationaliteid = $request->paysid;
            $travail->telephone = null;
            $travail->telephone2 = null;
            $travail->description = $request->mot;
            $travail->niveau_etudeid = null;
            $travail->savoirFaire = null;
            $travail->etapeid = $request->etapeid; //0 etape 1 , 1 etape 2
            $travail->inscrit_le = date('Y-m-d H-i-s');
            $travail->ip = $_SERVER['REMOTE_ADDR'];
            $travail->mois = date('m');
            $travail->annee = date('Y');
            $travail->save();

            if($travail->save()){

                    // gestion des date embauche, cessassion
                    $verif_action = ActionsCDC::where('actionid', 1)->where('travailleurid', $travail->id)->first();
                    if($verif_action){
                        return Redirect::back()->withErrors("Ce journalier a déja ete embauché, veuillez effectuer sa cessation afin de le reconduire .");
                    }else{
                        $actions = new ActionsCDC();
                        $actions->date_choisit = $request->dateembauche;
                        $actions->travailleurid = $travail->id;
                        $actions->userid = Auth::user()->id;
                        $actions->actionid = 1;//1: embauché(contrat normal)
                        $actions->save();
                    }

                    if( ($request->etapeid == 1) && ($actions->save())){

                        return Redirect::route('etapedeuxtravailleur', $travail->id)->withSuccess("Vous etes a la 2iem étape de l'enregistrement du travailleur : ".strtoupper($request->nom.' '.$request->prenom)." .");

                    }elseif($request->etapeid == 0){
                        return Redirect::back()->withSuccess("Vous avez terminé avec succès la première étape de l'enregistrement du travailleur.");
                    }
            }

        }

    }

    public function post_edit_travailleur($id, Request $request){

       /* $cmpte = Travailleur::where('matricule', '!=', $request->matricule)->first();
        dd('okkkkk');
        if($cmpte){
            return Redirect::back()->withErrors("Désoler le matricule existe deja dans la base de donnée, veuillez modifier a nouveau.");
        } else{*/

            $travail = Travailleur::find($id);
            //$datefincontrat = date( "Y-m-d", strtotime( "$request->dateembauche +330 day" ) );
            $travail->matricule = $request->matricule;
            $travail->civilite = $request->civilite;
            $travail->nom = $request->nom;
            $travail->prenom = $request->prenom;
            $travail->date_naissance = $request->datenaissance;
            $travail->numero_securite = $request->numero_securite;
            $travail->date_debut_contrat = $request->dateembauche;
            $travail->situation_mat = ($request->situation_mat);
            $travail->nombre_enfant = $request->nombre_enfant;
            $travail->lieu_naissance = $request->lieunaissance;
            $travail->uniteid = $request->uniteid;
            $travail->categorieid = $request->categorieid;
            $travail->fonction_entrepriseid = $request->fonction_entrepriseid;
            $travail->bulletin_modele_salarie = $request->bulletin_modele_salarie;
            $travail->type_salaire = null;
            $travail->email = $request->email;
            $travail->statutid = 2; // enregistrer etape deux
            $travail->userid = Auth::user()->id; // enregistrer par
            $travail->type_employer = $request->typeEmployer; //
            $travail->departementid = $request->departementid;
            $travail->equipeid = $request->equipeid;
            $travail->pieceidentite = strtoupper($request->pieceidentite);
            $travail->pieceidentite_livrele = $request->pieceidentite_livrele;
            $travail->pieceidentite_lieu = strtoupper($request->pieceidentite_lieu);

            $travail->nationaliteid = $request->paysid;
            $travail->telephone = $request->telephone;
            $travail->telephone2 = $request->telephone2;
            $travail->description = $request->mot;

            $travail->communeid = $request->communeid;

            $travail->niveau_etudeid = $request->niveau_etudeid;
            $travail->savoirFaire = null;
            $travail->etapeid = 2; // fin enregistrement contrat telecharger
            $travail->inscrit_le = date('Y-m-d H-i-s');
            $travail->ip = $_SERVER['REMOTE_ADDR'];
            $travail->save();
            /*if($travail->save()){*/
                return Redirect::route('lientelechargerContrat', $id)->withSuccess("Fin de l'enregistrement du travailleur : ".strtoupper($request->nom.' '.$request->prenom)." Vous pouvez télécharger son contrat .");
            //}

        //}

    }

    public function etapedeuxtravailleur($id){

        $departements = Departement::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();
        $pays = Pays::orderBy('id', 'DESC')->get();
        $equipes = Equipes::orderBy('id', 'DESC')->get();
        $fonctions = Fonction::orderBy('id', 'DESC')->get();
        $categories = Categories::orderBy('id', 'DESC')->get();
        $commune = Commune::orderBy('id', 'DESC')->get();
        $niveauEtudes = NiveauEtude::orderBy('id', 'DESC')->get();
        $edit = Travailleur::where('id', $id)->first();
        return view("travailleur.edit", compact('id', 'edit', 'pays', 'unites', 'niveauEtudes', 'commune', 'categories', 'fonctions', 'departements', 'equipes'));

    }

    public function lientelechargerContrat($idtravailleur){

        $departements = Departement::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();
        $pays = Pays::orderBy('id', 'DESC')->get();
        $equipes = Equipes::orderBy('id', 'DESC')->get();
        $edit_travailleur = Travailleur::where('id', $idtravailleur)->first();

        return view("travailleur.contrat", compact('idtravailleur', 'edit_travailleur', 'pays', 'unites', 'departements', 'equipes'));

    }

    public function telechargerContratJournalier(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if($request->has('download')){
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_journalier', compact('id','travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download('contrat_journalier.pdf');
        }

    }

    public function telechargerContratCessassion(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if($request->has('download')){
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_cessassion', compact('id','travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("contrat_cessassion_$travailleur->matricule.pdf");
        }

    }

    public function telechargerContratCertificatTravail(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if($request->has('download')){
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_certificat_travail', compact('id','travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("contrat_certificat_travail_$travailleur->matricule.pdf");
        }

    }

    public function telechargerContratDeclarationCnps(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if($request->has('download')){
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_declaration', compact('id','travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("contrat_declaration_cnps_$travailleur->matricule.pdf");
        }

    }

}
