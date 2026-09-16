<?php

namespace App\Http\Controllers;
use App\ActionsCDC;
use App\Precarites;
use App\Santes;
use App\Travailleur;

use App\Categories;
use App\Departement;
use App\HistoriqueUnite;
use App\Equipes;
use App\Fonction;
use App\NiveauEtude;
use App\Pays;
use App\Unites;
use App\Tenues;
use App\Commune;

use App\Variables;
use App\Exports\ArrayExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
//use Carbon\Carbon;
use Illuminate\Support\Carbon;

use Maatwebsite\Excel\Facades\Excel;

class RecruController extends Controller
{
    public function listetravailleurs(){
        //$termJ = 'J';
        $data_travailleurdeux = Travailleur::where('statutid', 1)->orderBy('id', 'DESC')->get();
        $equipesById = Equipes::whereIn('id', $data_travailleurdeux->pluck('equipeid'))->get()->keyBy('id');
        return view('travailleur.liste', compact('data_travailleurdeux', 'equipesById'));
    }

    public function liste_tous_travailleurs(){
        ini_set('memory_limit', '512M');
        set_time_limit(180);
        $termJ = 'J';
        $data_travailleurdeux = Travailleur::where('matricule', 'like', $termJ . '%')->where('equipeid', '!=' ,NULL)->actif()->orderBy('id', 'DESC')->get();
        return view('travailleur.listetravailleur', compact('data_travailleurdeux'));
    }

    public function liste_cessations(Request $request){
        ini_set('memory_limit', '512M');
        set_time_limit(180);
        $query = Travailleur::cesseConfirme();

        if (!empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $data_cessations = $query->orderBy('id', 'DESC')->get();
        return view('travailleur.listecessations', compact('data_cessations'));
    }

    public function liste_declarations(Request $request){
        $query = Travailleur::where('numero_securite', NULL);

        if (!empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }
        
        $data_declarations = $query->orderBy('id', 'DESC')->get();
        return view('travailleur.liste_declarations', compact('data_declarations'));
    }

    public function historiques_contrat($id){
		$data_unites = Unites::get();
		$data_equipes = Equipes::get();
		$infoContrat = ActionsCDC::where('travailleurid', $id)->where('debut_contrat', '!=' ,NULL)->where('fin_contrat', '!=' ,NULL)->get();
		$infoTenues = Tenues::where('travailleurid', $id)->get();
		$infoHistUnite = HistoriqueUnite::where('travailleurid', $id)->get();
        return view('contrat.detail_contrat', compact('infoContrat', 'id', 'data_equipes', 'data_unites', 'infoTenues', 'infoHistUnite'));
    }

    public function liste_travailleurs(Request $request){
        ini_set('memory_limit', '512M');
        set_time_limit(180);
        $query = Travailleur::where('matricule', '!=' ,NULL);

        if (!empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $data_Travailleur = $query->orderBy('id', 'DESC')->get();

        // Précalculé une seule fois pour éviter une requête ActionsCDC par ligne dans la vue.
        $cessesReels = ActionsCDC::where('actionid', 3)->pluck('travailleurid')->all();

        return view('travailleur.liste_tous_travailleur', compact('data_Travailleur', 'cessesReels'));
    }

    public function liste_certificat_travail(){
        $data_certificat_travail = Travailleur::where('etapeid', '!=' ,3)->where('etapeid',4)->orderBy('id', 'DESC')->get();
        $equipesById = Equipes::whereIn('id', $data_certificat_travail->pluck('equipeid'))->get()->keyBy('id');
        return view('travailleur.liste_certificat_travail', compact('data_certificat_travail', 'equipesById'));
    }

    public function historique(){
        $code = 1;
        $data_unites = Unites::get();
        $recherches = Travailleur::where('etapeid', '!=' ,3)->where('statutid', 9)->get();
        return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));
    }

    public function excel_download($code)
    {
        if($code){

            ini_set('memory_limit', '512M');
            set_time_limit(180);

            $tabCodes = explode("+", $code);
			$debut = $tabCodes['3'];
			$fin = $tabCodes['4'];
            $termE = 'E';
            $termJ = 'J';

            if( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 2) ){
                ///dd('okllllllllllddddddddddddd');
                $recherches = Travailleur::actif()
                    //->where('uniteid', '>=', $tabCodes['0'])
                    /*->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])*/
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Situation matrimoniale', 'Nombre enfants', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					
                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Situation matrimoniale'  => $customer->situation_mat,
                        'Nombre enfants'  => $customer->nombre_enfant,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journaliers'), 'liste_des_journaliers.xlsx');

            }elseif( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 2) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {

					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés'), 'liste_des_embauches.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 1) ){
                //dd('okkkkk');
                $recherches = Travailleur::actif()
                    ->where('uniteid', '>=', $tabCodes['0'])
                    /*
                    ->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])
                    */
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					
                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journalier par unites'), 'liste_journalier_par_unite.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 1) ){

                $recherches = Travailleur::actif()
                    ->where('uniteid', '>=', $tabCodes['0'])
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés par unites'), 'liste_embauches_par_unite.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 3) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journalier par unites'), 'liste_journalier_par_unite.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 3) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés par periode'), 'liste_embauches_par_periode.xlsx');

            }elseif(($tabCodes['1'] == 2) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journaliers en fin de contrat'), 'liste_journaliers_fin_contrat.xlsx');

            }elseif(($tabCodes['1'] == 1) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés en fin de contrat'), 'liste_embauches_fin_contrat.xlsx');

            }

        }

    }

    public function excel_download_quinzaine($code)
    {
        if($code){

            ini_set('memory_limit', '512M');
            set_time_limit(180);

            $tabCodes = explode("+", $code);
            //var_dump($tabCodes); die();
			$debut = $tabCodes['3'];
			$fin = $tabCodes['4'];
            $termE = 'E';
            $termJ = 'J';

            if( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 2) ){
                
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Situation matrimoniale', 'Nombre enfants', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Equipes', 'Catégorie', 'Emploi occupé', 
					'Bulletin modèle du salarié', 'Type de salaire',
					'Commune', 'Nationnalité', 'Date de naissance', 'Lieu de naissance', 'Piece identite', 'P.I delivre le', 'P.I delivre à');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$newPI_livrele = date('d/m/Y', strtotime($customer->pieceidentite_livrele));
					
					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$commune = Commune::where('id', $customer->communeid)->first();
					$pays = Pays::where('id', $customer->nationaliteid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
					
					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}
					
					if($commune == null){
						$communes = 'INCONNU';
					}else{
						$communes = $commune->label;
					}
					
					if($pays == null){
						$payss = 'INCONNU';
					}else{
						$payss = $pays->label;
					}
					
                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Situation matrimoniale'  => $customer->situation_mat,
                        'Nombre enfants'  => $customer->nombre_enfant,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'département'  => $customer->departementid,
                        'Equipes'  => $equipe,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
						
                        'Commune'  => $communes,
                        'Nationnalité'  => $payss,
                        'Date de naissance'  => $customer->date_naissance,
                        'Lieu de naissance'  => $customer->lieu_naissance,
                        'Piece identite'  => $customer->pieceidentite,
                        'Piece identite delivre le'  => $newPI_livrele,
                        'Piece identite delivre à'  => $customer->pieceidentite_lieu,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journaliers'), 'liste_des_journaliers.xlsx');

            }elseif( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 2) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Equipes', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 
					'Type de salaire', 'Commune', 'Nationnalité','Date de naissance', 'Lieu de naissance', 'Piece identite', 'P.I delivre le', 'P.I delivre à');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$newPI_livrele = date('d/m/Y', strtotime($customer->pieceidentite_livrele));
					
					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$commune = Commune::where('id', $customer->communeid)->first();
					$pays = Pays::where('id', $customer->nationaliteid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
					
					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}
					
					if($commune == null){
						$communes = 'INCONNU';
					}else{
						$communes = $commune->label;
					}
					
					if($pays == null){
						$payss = 'INCONNU';
					}else{
						$payss = $pays->label;
					}
					
                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
						
						'Commune'  => $communes,
                        'Nationnalité'  => $payss,
                        'Date de naissance'  => $newDate_naiss,
                        'Lieu de naissance'  => $customer->lieu_naissance,
                        'Piece identite'  => $customer->pieceidentite,
                        'Piece identite delivre le'  => $newPI_livrele,
                        'Piece identite delivre à'  => $customer->pieceidentite_lieu,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés'), 'liste_des_embauches.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 1) ){

                $recherches = Travailleur::actif()
                    ->where('uniteid', '>=', $tabCodes['0'])
                    /*
                    ->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])
                    */
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					
					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
					
					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}
					
                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journalier par unites'), 'liste_journalier_par_unite.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 1) ){

                $recherches = Travailleur::actif()
                    ->where('uniteid', '>=', $tabCodes['0'])
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Code département'  => $customer->departementid,
                        'Code service'  => $customer->equipeid,
                        'Code catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $customer->fonction_entrepriseid,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés par unites'), 'liste_embauches_par_unite.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 3) ){
				//dd('iciooooooooooooooooo');
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Service', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {

					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journalier par unites'), 'journalier_par_periode.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == 3) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Service', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés par periode'), 'embauches_par_periode.xlsx');

            }elseif(($tabCodes['1'] == 2) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Service', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des journaliers en fin de contrat'), 'journaliers_fin_contrat.xlsx');

            }elseif(($tabCodes['1'] == 1) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Service', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Civilité'  => $customer->civilite,
                        'Nom'  => $customer->nom,
                        'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                        'Date de naissance'  => $newDate_naiss,
                        'Téléphone'  => $customer->telephone,
                        'Numéro de portable'  => $customer->telephone2,
                        'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                        'Date de début de contrat'  => $newDate_debut,
                        'Date de fin de contrat'  => $newDate_fin,
                        'Département'  => $departement->label,
                        'Equipe'  => $equipe->label,
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                    );
                }

                return Excel::download(new ArrayExport($customer_array, 'Liste des embauchés en fin de contrat'), 'embauches_fin_contrat.xlsx');

            }

        }

    }

    /**
     * Export au format standard d'import employés Odoo (brouillon).
     * À ajuster une fois le vrai modèle d'import téléchargé depuis Odoo
     * (module Employés > Importer > Télécharger le modèle d'import).
     * N'affecte pas les exports SAGE/Paie existants.
     */
    public function excel_download_odoo($code)
    {
        if ($code) {

            ini_set('memory_limit', '512M');
            set_time_limit(180);

            $tabCodes = explode("+", $code);
            $termE = 'E';
            $termJ = 'J';
            $term = ($tabCodes['1'] == 1) ? $termE : $termJ;

            $query = Travailleur::actif()
                ->where('matricule', 'like', '%' . $term . '%');

            if (($tabCodes['2'] == 1) AND ($tabCodes['0'] != null)) {
                $query->where('uniteid', '>=', $tabCodes['0']);
            } elseif ($tabCodes['2'] == 3) {
                $query->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4']);
            } elseif ($tabCodes['2'] == 4) {
                $query->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4']);
            }
            // rech == 2 (Toutes les Unités) : pas de filtre supplémentaire

            $recherches = $query->get();

            $genderMap = [
                'Monsieur' => 'Male',
                'Madame' => 'Female',
                'Mademoiselle' => 'Female',
            ];

            $customer_array[] = array('Name', 'Identification No', 'SSN No', 'Job Position', 'Department',
                'Work Email', 'Work Phone', 'Mobile Phone', 'Date of Birth', 'Place of Birth', 'Gender',
                'Marital Status', 'Number of Dependent Children', 'Nationality (Country)',
                'Contract - Start Date', 'Contract - End Date');

            foreach ($recherches as $customer) {

                $departement = Departement::where('id', $customer->departementid)->first();
                $fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
                $pays = Pays::where('id', $customer->nationaliteid)->first();

                $situation = $customer->situation_mat;
                if (stripos($situation, 'celib') !== false || stripos($situation, 'ibataire') !== false) {
                    $maritalStatus = 'Single';
                } elseif (stripos($situation, 'veu') !== false) {
                    $maritalStatus = 'Widower';
                } elseif (stripos($situation, 'mari') !== false) {
                    $maritalStatus = 'Married';
                } else {
                    $maritalStatus = '';
                }

                $formatDate = function ($value) {
                    if (empty($value) || $value === '0000-00-00' || strtotime($value) === false) {
                        return '';
                    }
                    $formatted = date('Y-m-d', strtotime($value));
                    return $formatted < '1900-01-01' ? '' : $formatted;
                };

                $customer_array[] = array(
                    'Name' => trim($customer->nom . ' ' . $customer->prenom . ' ' . $customer->prenom_suite),
                    'Identification No' => $customer->matricule,
                    'SSN No' => $customer->numero_securite,
                    'Job Position' => $fonction->label ?? '',
                    'Department' => $departement->label ?? '',
                    'Work Email' => $customer->email,
                    'Work Phone' => $customer->telephone,
                    'Mobile Phone' => $customer->telephone2,
                    'Date of Birth' => $formatDate($customer->date_naissance),
                    'Place of Birth' => $customer->lieu_naissance,
                    'Gender' => $genderMap[$customer->civilite] ?? '',
                    'Marital Status' => $maritalStatus,
                    'Number of Dependent Children' => $customer->nombre_enfant,
                    'Nationality (Country)' => $pays->label ?? '',
                    'Contract - Start Date' => $formatDate($customer->date_debut_contrat),
                    'Contract - End Date' => $formatDate($customer->date_fin_contrat),
                );
            }

            return Excel::download(new ArrayExport($customer_array, 'Export Odoo'), 'export_odoo_employes.xlsx');
        }
    }

    public function excel_download_variables($code)
    {
        if($code){

            $tabCodes = explode("+", $code);

            if(count($tabCodes)){

                $recherches = Variables::where('debut', '>=', $tabCodes['0'])
                    ->where('debut', '<=', $tabCodes['1'])
                    ->where('cause', 1)
                    ->get();

                $customer_array[] = array('Matricule', 'Nom', 'Prénom', 'Jour', 'Variables', 'Nombre de jour');

                foreach($recherches as $customer) {

                    $debut = strtotime($customer->debut);
                    $fin = strtotime($customer->fin);
                    $dif = ceil(abs($fin - $debut) / 86400) + 1;

                    $nb_jour = intval($dif);

                    if($customer->type_variable == 1){
                        $jour = "DIMANCHE";
                    }elseif ($customer->type_variable == 2){
                        $jour = "FERIE";
                    }elseif ($customer->cas_variables == 3){
                        $jour = "JOUR OUVRABLE";
                    }

                    if($customer->cas_variables == 1){
                        $variables = "RETARD D'ENROLEMENT";
                    }elseif ($customer->cas_variables == 2){
                        $variables = "DEFAUT DE POINTAGE";
                    }elseif ($customer->cas_variables == 3){
                        $variables = "OUBLI DE POINTAGE";
                    }elseif ($customer->cas_variables == 3){
                        $variables = "DEFAUT D'EMPREINTE";
                    }

                    foreach (unserialize($customer->travailleurid) as $servaiable){
                        $travailleurVar = \App\Travailleur::where('matricule', $servaiable)->first();

                        if (!$travailleurVar) {
                            continue;
                        }

                        $customer_array[] = array(
                            'Matricule'  => $travailleurVar->matricule,
                            'Nom'  => $travailleurVar->nom,
                            'Prénom'  => $travailleurVar->prenom,
                            'Jour'  => $jour,
                            'Variables'  => $variables,
                            'Nombre de jour'  => $nb_jour,
                        );

                    }

                }


            }else{

                //if()
                $recherches = Variables::where('debut', '>=', $tabCodes['0'])
                    ->where('debut', '<=', $tabCodes['1'])
                    ->where('cas_variables', $tabCodes['2'])
                    ->where('cause', 1)
                    ->where('type_variable', $tabCodes['3'])
                    ->get();

                $customer_array[] = array('Matricule', 'Nom', 'Prénom', 'Jour', 'Variables', 'Nombre de jour');

                foreach($recherches as $customer) {

                    $debut = strtotime($customer->debut);
                    $fin = strtotime($customer->fin);
                    $dif = ceil(abs($fin - $debut) / 86400) + 1;

                    $nb_jour = intval($dif);

                    if($customer->type_variable == 1){
                        $jour = "DIMANCHE";
                    }elseif ($customer->type_variable == 2){
                        $jour = "FERIE";
                    }elseif ($customer->cas_variables == 3){
                        $jour = "JOUR OUVRABLE";
                    }

                    if($customer->cas_variables == 1){
                        $variables = "RETARD D'ENROLEMENT";
                    }elseif ($customer->cas_variables == 2){
                        $variables = "DEFAUT DE POINTAGE";
                    }elseif ($customer->cas_variables == 3){
                        $variables = "OUBLI DE POINTAGE";
                    }elseif ($customer->cas_variables == 3){
                        $variables = "DEFAUT D'EMPREINTE";
                    }

                    foreach (unserialize($customer->travailleurid) as $servaiable){
                        $travailleurVar = \App\Travailleur::where('matricule', $servaiable)->first();

                        if (!$travailleurVar) {
                            continue;
                        }

                        $customer_array[] = array(
                            'Matricule'  => $travailleurVar->matricule,
                            'Nom'  => $travailleurVar->nom,
                            'Prénom'  => $travailleurVar->prenom,
                            'Jour'  => $jour,
                            'Variables'  => $variables,
                            'Nombre de jour'  => $nb_jour,
                        );

                    }

                }


            }

                return Excel::download(new ArrayExport($customer_array, 'Liste des Variables'), 'liste_des_variables.xlsx');


        }

    }

    public function excel_download_fin_contrat(){

        $datejour = date('Y-m-d');
        $datefincontrat = date("Y-m-d", strtotime("$datejour +20 day" ));
        $fin_contrat = \App\Travailleur::where('statutid', '!=' ,4)->where('date_fin_contrat', '<=', $datefincontrat)->where('date_fin_contrat', '>', $datejour)->get();

        $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
            'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
            'Date de fin de contrat', 'Département', 'Equipe', 'Emploi occupé');


        foreach($fin_contrat as $customer)
        {
			
			$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
			$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
			$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

            $equipe = Equipes::where('id', $customer->equipeid)->first();
            $departement = Departement::where('id', $customer->departementid)->first();
			$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
			
			if($fonction == null){
				$fonctions = 'INCONNU';
			}else{
				$fonctions = $fonction->label;
			}
			

            $customer_array[] = array(
                'Matricule'  => $customer->matricule,
                'Civilité'  => $customer->civilite,
                'Nom'  => $customer->nom,
                'Prénom'  => $customer->prenom.''.$customer->prenom_suite,
                'Date de naissance'  => $newDate_naiss,
                'Téléphone'  => $customer->telephone,
                'Numéro de portable'  => $customer->telephone2,
                'Numéro de Sécurité Sociale'  => $customer->numero_securite,
                'Date de début de contrat'  => $newDate_debut,
                'Date de fin de contrat'  => $newDate_fin,
                'Département'  => $departement->label,
                'Equipe'  => $equipe->label,
                'Emploi occupé'  => $fonctions,
            );
        }


        return Excel::download(new ArrayExport($customer_array, 'Travailleurs en fin de contrat'), 'travailleurs_en_fin_de_contrat.xlsx');

    }

    public function post_search_varaiables(Request $request){

        if($request){

            if( ($request->variablesid == 4) &&  ($request->cas_variables == 5) ){

                $debut = $request->beginn; $fin = $request->endd;
                $code = $chaine=$debut.'+'.$fin;

                $recherches = Variables::where('debut', '>=', $request->beginn)
                    ->where('debut', '<=', $request->endd)
                    ->where('cause', 1)
                    ->get();

                $matricules = $recherches->flatMap(fn($rech) => unserialize($rech->travailleurid))->unique();
                $travailleursByMatricule = Travailleur::whereIn('matricule', $matricules)->get()->keyBy('matricule');

                return view('variables.historique', compact('recherches', 'code', 'travailleursByMatricule'));

            }else{

                $debut = $request->beginn; $fin = $request->endd; $cas = $request->cas_variables ; $variab = $request->variablesid;
                $code = $chaine=$debut.'+'.$fin.'+'.$cas.'+'.$variab;

                $recherches = Variables::where('debut', '>=', $request->beginn)
                    ->where('debut', '<=', $request->endd)
                    ->where('cas_variables', $request->cas_variables)
                    ->where('cause', 1)
                    ->where('type_variable', $request->variablesid)
                    ->get();

                $matricules = $recherches->flatMap(fn($rech) => unserialize($rech->travailleurid))->unique();
                $travailleursByMatricule = Travailleur::whereIn('matricule', $matricules)->get()->keyBy('matricule');

                return view('variables.historique', compact('recherches', 'code', 'travailleursByMatricule'));


            }


        }else{
            return view('error_277.277', compact('recherches', 'type'));
        }

    }

    public function post_search(Request $request){

        if($request){

            $termE = 'E';
            $termJ = 'J';

            // ====== RECHERCHES POUR TYPE EMBAUCHÉ (type_id == 1) ======

            // Embauché + Recherche par Unité
            if( ($request->type_id == 1) AND ($request->recherche == 1) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('uniteid', '=', $request->uniteid)
                    ->where('matricule', 'like', '%' . $termE . '%')->get();
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Embauché + Toutes les Unités
            elseif ( ($request->type_id == 1) AND ($request->recherche == 2) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')->get();
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Embauché + Période (date début de contrat)
            elseif ( ($request->type_id == 1) AND ($request->recherche == 3) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
					->where('date_debut_contrat', '>=', $request->beginn)
                    ->where('date_debut_contrat', '<=', $request->endd)
					->get();

                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Embauché + Fin de contrat
            elseif ( ($request->type_id == 1) AND ($request->recherche == 4) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termE . '%')
					->where('date_fin_contrat', '>=', $request->beginn)
                    ->where('date_fin_contrat', '<=', $request->endd)
					->get();

                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }

            // ====== RECHERCHES POUR TYPE JOURNALIER (type_id == 2) ======

            // Journalier + Recherche par Unité
            elseif ( ($request->type_id == 2) AND ($request->recherche == 1) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('uniteid', '=', $request->uniteid)
                    ->where('matricule', 'like', '%' . $termJ . '%')->get();
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Journalier + Toutes les Unités
            elseif ( ($request->type_id == 2) AND ($request->recherche == 2) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')->get();
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Journalier + Période (date début de contrat)
            elseif ( ($request->type_id == 2) AND ($request->recherche == 3) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
					->where('date_debut_contrat', '>=', $request->beginn)
                    ->where('date_debut_contrat', '<=', $request->endd)
					->get();

                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }
            // Journalier + Fin de contrat
            elseif ( ($request->type_id == 2) AND ($request->recherche == 4) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::actif()
                    ->where('matricule', 'like', '%' . $termJ . '%')
					->where('date_fin_contrat', '>=', $request->beginn)
                    ->where('date_fin_contrat', '<=', $request->endd)
					->get();

                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }

        }else{
            return view('error_277.277', compact('recherches', 'type'));
        }

    }

    public function declaration(Request $get){
        $id = $get->id;
        $data = Travailleur::find($id);
        return $data;
    }

    public function reconduireJournalier(Request $get){
        $id = $get->id;
        $data = Travailleur::find($id);
        return $data;
    }

    public function updatedeclaration(Request $request){

        if ($request->actionid == 8){

            $actionup = Travailleur::find($request->id);
            $actionup->userid = Auth::user()->id;
            $actionup->uniteid = $request->uniteid;
            $actionup->equipeid = $request->equipeid;
            $actionup->departementid = $request->departementid;
            $actionup->updated_at = Carbon::now();

            if($actionup->save()){
                $verif_unite = HistoriqueUnite::where('travailleurid', $request->id)->where('uniteid', $request->uniteid)->where('equipeid', $request->equipeid)->first();
                if($verif_unite){
                    return Redirect::back()->withErrors("Ce journalier a déja ete affecté a cette equipe de l'unité, veuillez changer d'equipe svp .");
                }else{
                    $unite = new HistoriqueUnite();;
                    $unite->travailleurid = $request->id;
                    $unite->uniteid = $request->uniteid;
                    $unite->equipeid = $request->equipeid;
                    $unite->departementid = $request->departementid;
                    $unite->date_choix = $request->datechoisit;
                    $unite->userid = Auth::user()->id;
                    $unite->updated_at = Carbon::now();
                    if($unite->save()){
                        return Redirect::back()->withSuccess("Ce journalier a changé d'equipe avec success.");
                    }
                }
            }

        }elseif ($request->actionid == 5){
			//declaration

            $actionup = Travailleur::find($request->id);
            $actionup->etapeid = $request->actionid;
            $actionup->userid = Auth::user()->id;
            $actionup->numero_securite = $request->numcnps;
            $actionup->date_cnps = $request->datechoisit;
            $actionup->updated_at = Carbon::now();

            if($actionup->save()){

                $verif_action = ActionsCDC::where('actionid', 5)->where('travailleurid', $request->id)->first();
                if($verif_action){
                    return Redirect::back()->withErrors("Ce journalier a déja ete declaré.");
                }else{
                    $actions = new ActionsCDC();
                    $actions->date_choisit = $request->datechoisit;
                    $actions->travailleurid = $request->id;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid;
                    if($actions->save() == true){
                        return Redirect::route('actionsContrat', $actions->travailleurid)->withSuccess("Merci de télécharger le contrat PDF ");
                    }
                }

            }

        }elseif ($request->actionid == 3){
            // cessation
            $actionup = Travailleur::find($request->id);
            $dejaCesse = $actionup->etapeid == 3; // évite de bloquer une 2e cessation légitime après une reconduction
            $actionup->etapeid = $request->actionid;
            $actionup->userid = Auth::user()->id;
            $actionup->date_fin_contrat = $request->datechoisit;
            $actionup->motif_fin_contrat = $request->motif_fin_contrat;
            $actionup->updated_at = Carbon::now();

            if($actionup->save()){

                if($dejaCesse){
                    return Redirect::back()->withErrors("Ce journalier a déja reçu une cessation .");
                }else{

                    $actions = new ActionsCDC();
                    $actions->date_choisit = $request->datechoisit;
                    $actions->fin_contrat = $actionup->date_fin_contrat;
                    $actions->debut_contrat = $actionup->date_debut_contrat;
                    $actions->travailleurid = $request->id;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid;

                    if($actions->save() == true){

                        /*$verif_Matricule = Precarites::where('matricule', $actionup->matricule)->first();
                        $verif_Matricule_update = Precarites::find($verif_Matricule->id);
                        $verif_Matricule_update->userid = Auth::user()->id;
                        $verif_Matricule_update->statutid = 3;
                        $verif_Matricule_update->updated_at = Carbon::now();
                        $verif_Matricule_update->save();*/
                        return Redirect::route('actionsContrat', $actions->travailleurid)->withSuccess("Merci de télécharger le contrat PDF ");

                    }
                }

            }
        }elseif ($request->actionid == 4){

            $actionup = Travailleur::find($request->id);
            $actionup->etapeid = $request->actionid;
            $actionup->userid = Auth::user()->id;
            $actionup->date_fin_contrat = $request->datechoisit;
            $actionup->updated_at = Carbon::now();

            if($actionup->save()){

                $verif_action = ActionsCDC::where('actionid', 4)->where('travailleurid', $request->id)->first();
                if($verif_action){
                    return Redirect::back()->withErrors("Ce journalier a déja reçu un certificat de travail.");
                }else{
                    $actions = new ActionsCDC();
                    $actions->date_choisit = $request->datechoisit;
					$actions->fin_contrat = $actionup->date_fin_contrat;
                    $actions->debut_contrat = $actionup->date_debut_contrat;
                    $actions->travailleurid = $request->id;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid;
                    if($actions->save() == true){
                        return Redirect::route('actionsContrat', $actions->travailleurid)->withSuccess("Merci de télécharger le contrat PDF ");
                    }
                }

            }

        }

    }

    public function patientrexu($id){

        $verif_Santes = Santes::where('id', $id)->first();

        if($verif_Santes){

            $actiSantes = Santes::find($verif_Santes->id);
            $actiSantes->statutid = 2; // reçu
            $actiSantes->recu_par = Auth::user()->id;
            $actiSantes->updated_at = Carbon::now();

            if($actiSantes->save()){
                return Redirect::back()->withSuccess("La réception du travailleur a été confirmé ");
            }

        }


    }
    public function updatereconduire(Request $request){


        if($request->actionid == 6){
            // reconduction
            $datefincontrat = date( "Y-m-d", strtotime( "$request->datechoisit +330 day" ) );

            $actionup = Travailleur::find($request->id);
            $actionup->etapeid = $request->actionid;
            $actionup->date_debut_contrat = $request->datechoisit;
            $actionup->date_fin_contrat = $request->datefin;
            $actionup->motif_fin_contrat = null; // nouvelle période : l'ancien motif ne s'applique plus
            $actionup->userid = Auth::user()->id;
            $actionup->updated_at = Carbon::now();

            if($actionup->save()){

                /*$verif_action = ActionsCDC::where('actionid', 6)->where('travailleurid', $request->id)->first();
                if($verif_action){
                    return Redirect::back()->withErrors("Ce journalier a déja ete reconduit, veuillez effectuer sa cessation afin de le reconduire .");
                }else{*/
                // ajout des new dates debut et fin (contrat) pour le suivie
                    $actions = new ActionsCDC();
                    $actions->date_choisit = $request->datechoisit;
                    $actions->debut_contrat = $request->datechoisit;
                    $actions->fin_contrat = $actionup->date_fin_contrat;
                    $actions->travailleurid = $request->id;
                    $actions->travailleur_mat = $actionup->matricule;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid; //Reconduire 6
                    if($actions->save() == true){
                        return Redirect::route('lientelechargerContrat', $actions->travailleurid)->withSuccess("Mr/Mme : ".strtoupper($actionup->nom.' '.$actionup->prenom.''.$actionup->prenom_suite)." a été reconduit, vous pouvez télécharger son contrat .");
                    }
                /*}*/

            }


        }elseif ($request->actionid == 7){
            // Precarites
            $actionup = Travailleur::find($request->id);

            if($actionup->etapeid != 3 ){
                return Redirect::back()->withErrors("Ce journalier n'a pas été cessé, merci d'effectuer sa cessation avant de payer la précarité.");
            }else{

                $verif_Matricule = Precarites::where('matricule', $actionup->matricule)->first();

                if($verif_Matricule){

                    $verif_Matricule_update = Precarites::find($verif_Matricule->id);
                    $verif_Matricule_update->userid = Auth::user()->id;
                    $verif_Matricule_update->statutid = 3; // payer et telecharger
                    $verif_Matricule_update->updated_at = Carbon::now();

                    if($verif_Matricule_update->save()){
                        return Redirect::route('actionsContrat', $actionup->id)->withSuccess("Merci de télécharger le contrat PDF ");
                    }

                }else{
                    return Redirect::back()->withErrors("Ce journalier n'a pas de HAO1 enregistré.");
                }

            }

        }

    }

    public function actionsContrat($id){

        $departements = Departement::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();
        $pays = Pays::orderBy('id', 'DESC')->get();
        $equipes = Equipes::orderBy('id', 'DESC')->get();
        $edit = Travailleur::where('id', $id)->first();
        return view("travailleur.actions_contrat", compact('id', 'edit', 'pays', 'unites', 'departements', 'equipes'));

    }


}
