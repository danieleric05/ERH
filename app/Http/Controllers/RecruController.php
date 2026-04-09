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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
//use Carbon\Carbon;
use Illuminate\Support\Carbon;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class RecruController extends Controller
{
    public function listetravailleurs(){
        //$termJ = 'J';
        $data_travailleurdeux = Travailleur::where('statutid', 1)->orderBy('id', 'DESC')->get();
        return view('travailleur.liste', compact('data_travailleurdeux'));
    }

    public function liste_tous_travailleurs(){
        $termJ = 'J';
        $data_travailleurdeux = Travailleur::where('matricule', 'like', '%' . $termJ . '%')->where('equipeid', '!=' ,NULL)->where('statutid', '!=', 1)->where('etapeid', '!=', 3)->where('etapeid', '!=', 4)->orderBy('id', 'DESC')->get();
        return view('travailleur.listetravailleur', compact('data_travailleurdeux'));
    }

    public function liste_cessations(){
        $data_cessations = Travailleur::where('etapeid',3)->orderBy('id', 'DESC')->get();
        return view('travailleur.listecessations', compact('data_cessations'));
    }

    public function liste_declarations(){
        $data_declarations = Travailleur::where('numero_securite', NULL)->orderBy('id', 'DESC')->get();
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

    public function liste_travailleurs(){
        $data_Travailleur = Travailleur::where('matricule', '!=' ,NULL)->orderBy('id', 'DESC')->get();
        return view('travailleur.liste_tous_travailleur', compact('data_Travailleur'));
    }

    public function liste_certificat_travail(){
        $data_certificat_travail = Travailleur::where('etapeid', '!=' ,3)->where('etapeid',4)->orderBy('id', 'DESC')->get();
        return view('travailleur.liste_certificat_travail', compact('data_certificat_travail'));
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

            $tabCodes = explode("+", $code);
			$debut = $tabCodes['3'];
			$fin = $tabCodes['4'];
            $termE = 'E';
            $termJ = 'J';

            if( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 2) ){
                ///dd('okllllllllllddddddddddddd');
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    //->where('uniteid', '>=', $tabCodes['0'])
                    /*->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])*/
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Situation matrimoniale', 'Nombre enfants', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$commune = Commune::where('id', $customer->communeid)->first();
					$communes = $commune ? $commune->label : 'INCONNU';

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
                        'Commune'  => $communes,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Liste_des_journaliers.xlsx');

            }elseif( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == null) ){

                $recherches = Travailleur::where('etapeid', '!=', 3)
                    //->where('uniteid', '>=', $tabCodes['0'])
                    /*
                    ->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])
                    */
                    ->where('matricule', 'like', '%' . $termE . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {

					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$commune = Commune::where('id', $customer->communeid)->first();
					$communes = $commune ? $commune->label : 'INCONNU';

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
                        'Commune'  => $communes,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Liste_des_embauches.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 1) ){
                //dd('okkkkk');
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('uniteid', '>=', $tabCodes['0'])
                    /*
                    ->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])
                    */
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$commune = Commune::where('id', $customer->communeid)->first();
					$communes = $commune ? $commune->label : 'INCONNU';

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
                        'Commune'  => $communes,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Liste_Journalier_Par_Unite.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 3) ){
				
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$commune = Commune::where('id', $customer->communeid)->first();
					$communes = $commune ? $commune->label : 'INCONNU';

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
                        'Commune'  => $communes,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Liste_Journalier_Par_Periode.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));
					$commune = Commune::where('id', $customer->communeid)->first();
					$communes = $commune ? $commune->label : 'INCONNU';

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
                        'Commune'  => $communes,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Journalier_Fin_Contrat.xlsx');

            }

        }

    }

    public function excel_download_quinzaine($code)
    {
        if($code){

            $tabCodes = explode("+", $code);
            //var_dump($tabCodes); die();
			$debut = $tabCodes['3'];
			$fin = $tabCodes['4'];
            $termE = 'E';
            $termJ = 'J';

            if( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 2) ){
                
                $recherches = Travailleur::where('etapeid', '!=', 3)
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
                        'Equipes'  => $equipe ? $equipe->label : 'INCONNU',
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

                return Excel::download(new ArrayExport($customer_array), 'Liste_Journaliers.xlsx');

            }elseif( ($tabCodes['0'] == null) AND ($tabCodes['1'] == 1) AND ($tabCodes['2'] == null) ){
				//var_dump('oklllllllllllll');
                $recherches = Travailleur::where('etapeid', '!=', 3)
                   
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

                return Excel::download(new ArrayExport($customer_array), 'Liste_Embauches.xlsx');

            }elseif(($tabCodes['0'] != null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 1) ){
                
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('uniteid', '>=', $tabCodes['0'])
                    /*
                    ->where('date_debut_contrat', '>=', $tabCodes['0'])
                    ->where('date_debut_contrat', '<=', $tabCodes['1'])
                    */
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Code département', 'Code service', 'Code catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {
					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
					$commune = Commune::where('id', $customer->communeid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}
					$communes = $commune ? $commune->label : 'INCONNU';

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
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Liste_Journalier_Par_Unite.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 3) ){
				//dd('iciooooooooooooooooo');
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_debut_contrat', '>=', $tabCodes['3'])
                    ->where('date_debut_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Service', 'Catégorie', 'Emploi occupé', 'Bulletin modèle du salarié', 'Type de salaire', 'Commune');

                foreach($recherches as $customer)
                {

					$newDate_debut = date('d/m/Y', strtotime($customer->date_debut_contrat));
					$newDate_fin = date('d/m/Y', strtotime($customer->date_fin_contrat));
					$newDate_naiss = date('d/m/Y', strtotime($customer->date_naissance));

					$equipe = Equipes::where('id', $customer->equipeid)->first();
					$departement = Departement::where('id', $customer->departementid)->first();
					$fonction = Fonction::where('id', $customer->fonction_entrepriseid)->first();
					$commune = Commune::where('id', $customer->communeid)->first();

					if($fonction == null){
						$fonctions = 'INCONNU';
					}else{
						$fonctions = $fonction->label;
					}
					$communes = $commune ? $commune->label : 'INCONNU';

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
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Journalier_Par_Periode.xlsx');

            }elseif(($tabCodes['0'] == null ) AND ($tabCodes['1'] == 2) AND ($tabCodes['2'] == 4) ){

                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('matricule', 'like', '%' . $termJ . '%')
                    ->where('date_fin_contrat', '>=', $tabCodes['3'])
                    ->where('date_fin_contrat', '<=', $tabCodes['4'])
                    ->get();

                $customer_array[] = array('Matricule', 'Civilité', 'Nom', 'Prénom', 'Situation matrimoniale', 'Nombre enfants', 'Date de naissance', 'Téléphone',
                    'Numéro de portable', 'Numéro de Sécurité Sociale', 'Date de début de contrat',
                    'Date de fin de contrat', 'Département', 'Equipes', 'Catégorie', 'Emploi occupé',
                    'Bulletin modèle du salarié', 'Type de salaire',
                    'Commune', 'Nationnalité', 'Lieu de naissance', 'Piece identite', 'P.I delivre le', 'P.I delivre à');

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

					$fonctions = $fonction ? $fonction->label : 'INCONNU';
					$communes = $commune ? $commune->label : 'INCONNU';
					$payss = $pays ? $pays->label : 'INCONNU';

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
                        'Département'  => $customer->departementid,
                        'Equipes'  => $equipe ? $equipe->label : 'INCONNU',
                        'Catégorie'  => $customer->categorieid,
                        'Emploi occupé'  => $fonctions,
                        'Bulletin modèle du salarié'  => $customer->bulletin_modele_salarie,
                        'Type de salaire'  => $customer->type_salaire,
                        'Commune'  => $communes,
                        'Nationnalité'  => $payss,
                        'Lieu de naissance'  => $customer->lieu_naissance,
                        'Piece identite'  => $customer->pieceidentite,
                        'Piece identite delivre le'  => $newPI_livrele,
                        'Piece identite delivre à'  => $customer->pieceidentite_lieu,
                    );
                }

                return Excel::download(new ArrayExport($customer_array), 'Journalier_Fin_Contrat.xlsx');

            }

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
                        $prenom = \App\Travailleur::where('matricule', $servaiable)->first()->prenom ;
                        $nom = \App\Travailleur::where('matricule', $servaiable)->first()->nom ;
                        $trava = \App\Travailleur::where('matricule', $servaiable)->first()->matricule ;

                        $customer_array[] = array(
                            'Matricule'  => $trava,
                            'Nom'  => $nom ,
                            'Prénom'  => $prenom,
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
                        $prenom = \App\Travailleur::where('matricule', $servaiable)->first()->prenom ;
                        $nom = \App\Travailleur::where('matricule', $servaiable)->first()->nom ;
                        $trava = \App\Travailleur::where('matricule', $servaiable)->first()->matricule ;

                        $customer_array[] = array(
                            'Matricule'  => $trava,
                            'Nom'  => $nom ,
                            'Prénom'  => $prenom,
                            'Jour'  => $jour,
                            'Variables'  => $variables,
                            'Nombre de jour'  => $nb_jour,
                        );

                    }

                }


            }

                return Excel::download(new ArrayExport($customer_array), 'Liste_Variables.xlsx');


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


        return Excel::download(new ArrayExport($customer_array), 'Travailleurs_Fin_Contrat.xlsx');

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

                return view('variables.historique', compact('recherches', 'code'));

            }else{

                $debut = $request->beginn; $fin = $request->endd; $cas = $request->cas_variables ; $variab = $request->variablesid;
                $code = $chaine=$debut.'+'.$fin.'+'.$cas.'+'.$variab;

                $recherches = Variables::where('debut', '>=', $request->beginn)
                    ->where('debut', '<=', $request->endd)
                    ->where('cas_variables', $request->cas_variables)
                    ->where('cause', 1)
                    ->where('type_variable', $request->variablesid)
                    ->get();

                return view('variables.historique', compact('recherches', 'code'));


            }


        }else{
            return view('error_277.277', compact('recherches', 'type'));
        }

    }

    public function post_search(Request $request){

        if($request){

            $termE = 'E';
            $termJ = 'J';
            if( ($request->type_id == 1) AND ($request->recherche == null) AND ($request->uniteid == null) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;
                $data_unites = Unites::get();
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    //->where('uniteid', '<=', $request->uniteid)
                    ->where('matricule', 'like', '%' . $termE . '%')->get();
                //$recherches = Travailleur::where('etapeid', '!=', 3)->where('datepub', '>=', $request->beginn)->where('matricule', 'like', '%' . $termE . '%') ->where('datepub', '<=', $request->endd)->get();
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));


            }elseif ( ($request->type_id == 2) AND ($request->recherche == 1) ){

                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('uniteid', '=', $request->uniteid)
                    ->where('matricule', 'like', '%' . $termJ . '%')->get();
                /*$recherches = Travailleur::where('etapeid', '!=', 3)->where('date_debut_contrat', '>=', $request->beginn)
                                                                    ->where('date_debut_contrat', '<=', $request->endd)
                                                                    ->where('matricule', 'like', '%' . $termJ . '%')->get();*/
                //dd($recherches);
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }elseif ( ($request->recherche == 3) AND ($request->type_id == 2) ){
				
                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;
				
                $data_unites = Unites::get();
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    ->where('matricule', 'like', '%' . $termJ . '%')
					->where('date_debut_contrat', '>=', $request->beginn)
                    ->where('date_debut_contrat', '<=', $request->endd)
					->get();
					
				//dd($recherches);
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }elseif ( ($request->type_id == 2) AND ($request->recherche == 2) AND ($request->uniteid == null) ){
				//dd('okkkkkkkkk');
                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;

                $data_unites = Unites::get();
                $recherches = Travailleur::where('etapeid', '!=', 3)
                    //->where('uniteid', '<=', $request->uniteid)
                    ->where('matricule', 'like', '%' . $termJ . '%')->get();
                /*$recherches = Travailleur::where('etapeid', '!=', 3)->where('date_debut_contrat', '>=', $request->beginn)
                                                                    ->where('date_debut_contrat', '<=', $request->endd)
                                                                    ->where('matricule', 'like', '%' . $termJ . '%')->get();*/
                //dd($recherches);
                return view('travailleur.historique', compact('recherches', 'data_unites', 'code'));

            }elseif ( ($request->type_id == 2) AND ($request->recherche == 4) AND ($request->uniteid == null) ){
				
				
                $unite = $request->uniteid; $debut = $request->beginn; $fin = $request->endd; $type = $request->type_id; $rech = $request->recherche;
                $code = $chaine=$unite.'+'.$type.'+'.$rech.'+'.$debut.'+'.$fin;
				
                $data_unites = Unites::get();
                $recherches = Travailleur::where('etapeid', '!=', 3)
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
            $actionup->save();

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
                    $unite->save();
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
            $actionup->save();

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
                    $actions->save();
                    if($actions->save() == true){
                        return Redirect::route('actionsContrat', $actions->travailleurid)->withSuccess("Merci de télécharger le contrat PDF ");
                    }
                }

            }

        }elseif ($request->actionid == 3){
            // cessation
            $actionup = Travailleur::find($request->id);
            $actionup->etapeid = $request->actionid;
            $actionup->userid = Auth::user()->id;
            $actionup->date_fin_contrat = $request->datechoisit;
            $actionup->motif_fin_contrat = $request->motif_fin_contrat;
            $actionup->updated_at = Carbon::now();
            $actionup->save();

            if($actionup->save()){

                $verif_action = ActionsCDC::where('actionid', 3)->where('travailleurid', $request->id)->first();

                if($verif_action){
                    return Redirect::back()->withErrors("Ce journalier a déja reçu une cessation .");
                }else{

                    $actions = new ActionsCDC();
                    $actions->date_choisit = $request->datechoisit;
                    $actions->fin_contrat = $actionup->date_fin_contrat;
                    $actions->debut_contrat = $actionup->date_debut_contrat;
                    $actions->travailleurid = $request->id;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid;
                    $actions->save();

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
            $actionup->save();

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
                    $actions->save();
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
            $actiSantes->save();

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
            $actionup->userid = Auth::user()->id;
            $actionup->updated_at = Carbon::now();
            $actionup->save();

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
                    $actions->travailleur_mat = $actionup->travailleur_mat;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = $request->actionid; //Reconduire 6
                    $actions->save();
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
                    $verif_Matricule_update->save();

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
