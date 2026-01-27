<?php

namespace App\Http\Controllers;

use App\ActionsCDC;
use App\ArticleRecu;
use App\Autorisations;
use App\Categories;
use App\Commune;
use App\Departement;
use App\Equipes;
use App\Fonction;
use App\ApproTenues;
use App\HAO1;
use App\NiveauEtude;
use App\Pays;
use App\Precarites;
use App\HistoriqueUnite;
use App\Santes;
use App\Tenues;
use App\Sanctions;
use App\Travailleur;
use App\Unites;
use App\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Types\Null_;
use PDF;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployerController extends Controller
{

    public function detect_matricul()
    {

        $listesante = ActionsCDC::where('debut_contrat', '=', NULL)->where('fin_contrat', '=', NULL)->get();

        foreach ($listesante as $sante) {

            $debut = Travailleur::where('id', $sante->travailleurid)->first()->date_debut_contrat;
            $fin = Travailleur::where('id', $sante->travailleurid)->first()->date_fin_contrat;

            $travail = ActionsCDC::find($sante->id);
            $travail->debut_contrat = $debut;
            $travail->fin_contrat = $fin;
            $travail->save();
        }

        dd('okkkkkkkkkkkk');


        $data_ArticleRecu = \App\ArticleRecu::orderBy('id', 'DESC')->get();
        return view("tenues.appro_stock", compact('tenues_nouvelle', 'tenues_ancienne', 'data_ArticleRecu'));
    }

    public function post_gestion_tenue(Request $request)
    {

        $matricule = \App\Travailleur::where('id', $request->travailleurid)->first()->matricule;
        $article = ArticleRecu::where('id', strtoupper($request->tenuerecu))->first();

        if ($article->quantite_en_stock > 0) {

            $verif_mat = Tenues::where('travailleurid', strtoupper($request->travailleurid))->where('etat', 1)->first();
            $verif_matcount = Tenues::where('travailleurid', strtoupper($request->travailleurid))->where('etat', 1)->count();
            /*if( ($verif_mat) && ($verif_matcount == 1) ){
                return Redirect::back()->withErrors("Désoler ce matricule a déja une tenue en bonne etat, veuillez changer l'etat de la tenue ".strtoupper($article->label)." svp.");
            }else*/
            if (($verif_mat) && ($verif_matcount == 2)) {
                return Redirect::back()->withErrors("Désoler ce matricule a déja deux tenues en bonne etat, veuillez changer l'etat de la tenue " . strtoupper($article->label) . " svp.");
            } else {

                $tenue = new Tenues();
                $tenue->travailleurid = $request->travailleurid;
                $tenue->travailleur_mat = $matricule;
                $tenue->datereception = $request->datereception;
                $tenue->tenuerecu = $request->tenuerecu;
                $tenue->detail_tenue = $request->detail_tenue;
                $tenue->services = $request->services;
                $tenue->detail_chaussure = $request->detail_chaussure;
                $tenue->etat = $request->etat; //1 bon etat 2 mauvais etat
                $tenue->userid = Auth::user()->id;
                $tenue->mois = date('m');
                $tenue->annee = date('Y');
                $tenue->save();
                if ($tenue->save()) {

                    $articleUpda = ArticleRecu::find($article->id);
                    $articleUpda->quantite_en_stock -= intval(1);
                    $articleUpda->save();
                    if ($articleUpda->save()) {
                        return Redirect::back()->withSuccess("La tenue " . strtoupper($article->label) . " du travailleur a été enregistré avec succès ");
                    }
                }
            }
        } else {
            return Redirect::back()->withErrors("Désoler l'article demandé n'est pas disponible en stock.");
        }
    }


    public function post_search_histo_precarite(Request $request)
    {


        if (isset($request) and !empty($request)) {

            if ($request->variables_type == 1) {
                $term = 'J';
                $recherches = Precarites::where('dateha', '>=', $request->beginn)->where('dateha', '<=', $request->endd)->where('matricule', 'like', '%' . $term . '%')->get();

                $debut = $request->beginn;
                $fin = $request->endd;
                $code = $debut . '+' . $fin . '+' . $term;

                $type = $request->variables_type;
                $variables = Variables::where('debut', '>=', $request->beginn)->where('debut', '<=', $request->endd)->where('type_variable', 2)->get();

                return view('precarite.historique', compact('recherches', 'variables', 'type', 'code'));
            } elseif ($request->variables_type == 2) {
                $term = 'E';
                $recherches = Precarites::where('dateha', '>=', $request->beginn)->where('dateha', '<=', $request->endd)->where('matricule', 'like', '%' . $term . '%')->get();

                $debut = $request->beginn;
                $fin = $request->endd;
                $code = $debut . '+' . $fin . '+' . $term;

                $type = $request->variables_type;
                $variables = Variables::where('debut', '>=', $request->beginn)->where('debut', '<=', $request->endd)->where('type_variable', 2)->get();

                return view('precarite.historique', compact('recherches', 'variables', 'type', 'code'));
            }
        } else {
            return Redirect::back()->withErrors("Veuillez selectionner les champs.");
        }
    }

    public function post_precarite_ho(Request $request)
    {

        if ($request->hasFile('fichiers')) {

            /*$verif_traitement = HAO1::where('statutid', 1)->get();
                    if(!isset($verif_traitement)){
                        return Redirect::back()->withErrors("Les HA01 insérés ne sont pas encore traités, veuillez le faire avant de passer a un autre.");
                    }else{
                */

            $verif_traitement = HAO1::where('statutid', 1)->count();
            $verif_traitement_ha01 = HAO1::where('statutid', 2)->count();
            $verif_trait = HAO1::where('statutid', 1)->get();

            $fileextension = $request->file('fichiers')->getClientOriginalExtension();
            $xls = 'xls';
            $xlsx = 'xlsx';
            if (!($fileextension)) {

                return Redirect::back()->withErrors("Désoler seul les fichiers excels sont traités.");
            } else {

                $file = $request->file('fichiers')->getRealPath();

                $data = Excel::load($file)->get();

                if ($verif_traitement > 0) {

                    foreach ($data->toArray() as $key => $value) {
                        foreach ($value as $row) {

                            foreach ($verif_trait as $verifC) {

                                if ($verifC->matricule == $row['matricule']) {
                                    $plus = $row['valeur'] + $verifC->variable;
                                    //var_dump($plus);
                                    $newHA01 = HAO1::find($verifC->id);
                                    $newHA01->valeur_ha01 = $plus;
                                    $newHA01->statutid = 2;
                                    $newHA01->save();
                                }
                            }
                        }
                    }

                    return Redirect::back()->withSuccess("Importation des HA01 de la paie effectuée avec succèes.");
                } elseif ($verif_traitement == 0) {

                    if ($data->count() > 0) {
                        foreach ($data->toArray() as $key => $value) {
                            foreach ($value as $row) {
                                $insert_data[] = array(
                                    'matricule'  => $row['matricule'],
                                    'element'   => $row['element'],
                                    'code'   => $row['codeelement'],
                                    'variable'    => $row['valeur'],
                                    'statutid'    => 1,
                                    'dateha'  => date('Y-m-d'),
                                    'userid'   => Auth::user()->id,
                                );
                            }
                        }

                        if (!empty($insert_data)) {
                            DB::table('e_ha01')->insert($insert_data);
                        }

                        return Redirect::back()->withSuccess("Importation effectuée avec succèes.");
                    }
                }
            }

            if ($verif_traitement_ha01 > 0) {
                return Redirect::back()->withErrors("Désoler Veuillez finaliser les HA01 en cour.");
            }
        }
    }

    public function finaliser_ha01()
    {

        $verif_trait = HAO1::where('statutid', 2)->get();

        foreach ($verif_trait as $row) {
            $insert_data_final[] = array(
                'matricule'  => $row['matricule'],
                'element'   => $row['element'],
                'code'   => $row['codeelement'],
                'variable'    => $row['valeur'],
                'statutid'    => 1,
                'dateha'  => date('Y-m-d'),
                'userid'   => Auth::user()->id,
            );
        }

        if (!empty($insert_data_final)) {
            DB::table('e_ha01_final')->insert($insert_data_final);
        }

        return Redirect::back()->withSuccess("Importation effectuée avec succèes.");
    }

    public function listemissions()
    {
        $liste_mission = Autorisations::where('motif_absence', 6)->orderBy('id', 'DESC')->get();
        $tenues = Tenues::orderBy('id', 'DESC')->get();
        return view("autorisations.liste_mission", compact('liste_mission', 'tenues'));
    }

    public function listeautorisations()
    {
        $liste_auto = Autorisations::where('motif_absence', '!=', 6)->orderBy('id', 'DESC')->get();
        $tenues = Tenues::orderBy('id', 'DESC')->get();
        return view("autorisations.liste_autorisations", compact('liste_auto', 'tenues'));
    }

    public function listevariables_manuelle()
    {
        $tenues = Tenues::orderBy('id', 'DESC')->get();
        return view("variables.listevariables_manuelle", compact('departements', 'tenues'));
    }

    public function listeprecarites()
    {
        $Precarites = Precarites::where('statutid', 3)->orderBy('id', 'DESC')->get();
        $tenues = Tenues::orderBy('id', 'DESC')->get();
        return view("precarite.listeprecarites", compact('Precarites', 'tenues'));
    }

    public function historiques_variables()
    {
        $code = '';
        $recherches  = Variables::where('cause', 1)->where('debut', date('Y-m-d'))->orderBy('id', 'DESC')->get();
        return view("variables.historique", compact('departements', 'recherches', 'code'));
    }

    public function historique_ha01()
    {
        $code = '';
        $recherches  = Precarites::where('dateha', date('Y-m-d'))->orderBy('id', 'DESC')->get();
        $variables  = Variables::where('debut', date('Y-m-d'))->orderBy('id', 'DESC')->get();
        return view("precarite.historique", compact('recherches', 'variables', 'code'));
    }

    public function listevariables_heure_supp()
    {
        $tenues = Tenues::orderBy('id', 'DESC')->get();
        return view("variables.liste_heure_sup", compact('departements', 'tenues'));
    }

    public function listetenues()
    {

        $tenues = Tenues::orderBy('id', 'DESC')->get();
        $departements = Departement::orderBy('id', 'DESC')->get();
        return view("tenues.liste", compact('departements', 'tenues'));
    }

    public function stock_tenues()
    {
        $tenues_nouvelle = Tenues::where('etat', 1)->where('etat', 1)->where('etat', 1)->orderBy('id', 'DESC')->get();
        $tenues_ancienne = Tenues::where('etat', 2)->orderBy('id', 'DESC')->get();
        $articleStock = ArticleRecu::orderBy('id', 'DESC')->get();
        return view("tenues.stock", compact('tenues_nouvelle', 'tenues_ancienne', 'articleStock'));
    }

    public function appro_stock()
    {
        $data_ArticleRecu = \App\ArticleRecu::orderBy('id', 'DESC')->get();
        $data_ApproTenues = \App\ApproTenues::orderBy('id', 'DESC')->get();
        return view("tenues.appro_stock", compact('data_ApproTenues', 'data_ArticleRecu'));
    }

    public function post_appro_stock(Request $request)
    {

        $id_article = ArticleRecu::where('id', strtoupper($request->tenuerecu))->first();

        if ($id_article) {


            $approvi = new ApproTenues();
            $approvi->quantite = $request->quantite;
            $approvi->fournisseur = strtoupper($request->fournisseur);
            $approvi->date_recep = $request->date_reception;
            $approvi->articleid = $request->tenuerecu;
            $approvi->userid = Auth::user()->id;
            $approvi->save();
            if ($approvi->save()) {

                $article = ArticleRecu::find($id_article->id);
                $article->quantite_en_stock += intval($request->quantite);
                if ($request->fournisseur) {
                    $article->fournisseur = strtoupper($request->fournisseur);
                }
                $article->date_reception = $request->date_reception;
                $article->statutid = 1;
                $article->userid = Auth::user()->id;
                $article->save();
                if ($article->save()) {
                    return Redirect::back()->withSuccess("L'approvisionnement des " . strtoupper($id_article->label) . " a été effectué avec succès, disponible en stock : " . $article->quantite_en_stock . "");
                }
            }
        }
    }


    public function post_travailleur(Request $request)
    {

        $verif_mat = Travailleur::where('matricule', strtoupper($request->matricule))->first();

        if ($verif_mat) {
            return Redirect::back()->withErrors("Désoler ce matricule a été deja utilisé.");
        } else {

            $chaine = $request->matricule;
            $morceau = substr($chaine, 0, 1);

            $nbre_car = strlen($request->prenom);
            $prenom_un = substr(strtoupper($request->prenom), 0, 19);
            $prenom_deux = substr(strtoupper($request->prenom), 19, $nbre_car);
            //dd($request->prenom.'----'.$prenom_un.'--'.$prenom_deux);
            $tabDate = explode("-", date('Y-m-d'));
            $travail = new Travailleur();
            $travail->matricule = strtoupper($request->matricule);
            $travail->civilite = NULL;
            $travail->nom = strtoupper($request->nom);
            $travail->prenom = $prenom_un;

            if ($prenom_deux == false) {
                $travail->prenom_suite = null;
            } else {
                $travail->prenom_suite = $prenom_deux;
            }

            $travail->date_naissance = $request->datenaissance;
            $travail->numero_securite = Null;
            $travail->situation_mat = $request->situation_mat;
            $travail->nombre_enfant = null;
            $travail->uniteid = $request->uniteid;
            $travail->categorieid = null;
            $travail->fonction_entrepriseid = null;
            $travail->email = null;
            $travail->statutid = 1; // enregistrer etape 1
            $travail->userid = Auth::user()->id; // enregistrer par
            $travail->equipeid = $request->equipeid;
            //dd($request->equipeid.$request->uniteid);
            if ($morceau == 'J') {
                $datefincontrat = date("Y-m-d", strtotime("$request->date_embauche_journalier +320 day"));
                $travail->date_debut_contrat = $request->date_embauche_journalier;
                $travail->date_fin_contrat = $datefincontrat;
                $travail->type_employer = 1;

                // --------creation de l'identifiant ---------------
                $unite_id = '';
                if ($request->uniteid == 7) {
                    $unite_id = 0;
                } elseif ((($request->uniteid == 2) || ($request->uniteid == 1)) && ($request->equipeid == 4)) {
                    $unite_id = 1;
                    $equip_id = 1;
                } elseif ((($request->uniteid == 2) || ($request->uniteid == 1)) && ($request->equipeid == 5)) {
                    $unite_id = 1;
                    $equip_id = 2;
                } elseif ((($request->uniteid == 2) || ($request->uniteid == 1)) && ($request->equipeid == 6)) {
                    $unite_id = 1;
                    $equip_id = 3;
                } elseif ((($request->uniteid == 2) || ($request->uniteid == 1)) && ($request->equipeid == 49)) {
                    $unite_id = 1;
                    $equip_id = 0;
                } elseif (($request->uniteid == 6) && ($request->equipeid == 1)) {
                    $unite_id = 4;
                    $equip_id = 1;
                } elseif (($request->uniteid == 6) && ($request->equipeid == 2)) {
                    $unite_id = 4;
                    $equip_id = 2;
                } elseif (($request->uniteid == 6) && ($request->equipeid == 3)) {
                    $unite_id = 4;
                    $equip_id = 3;
                } elseif (($request->uniteid == 6) && ($request->equipeid == 49)) {
                    $unite_id = 4;
                    $equip_id = 0;
                } elseif (($request->uniteid == 4) && ($request->equipeid == 61)) {
                    $unite_id = 3;
                    $equip_id = 1;
                } elseif (($request->uniteid == 4) && ($request->equipeid == 39)) {
                    $unite_id = 3;
                    $equip_id = 2;
                } elseif (($request->uniteid == 4) && ($request->equipeid == 62)) {
                    $unite_id = 3;
                    $equip_id = 3;
                } elseif (($request->uniteid == 4) && ($request->equipeid == 49)) {
                    $unite_id = 3;
                    $equip_id = 0;
                } elseif (($request->uniteid == 1) && ($request->equipeid == 46)) {
                    $unite_id = 6;
                    $equip_id = 1;
                } elseif (($request->uniteid == 1) && ($request->equipeid == 47)) {
                    $unite_id = 7;
                    $equip_id = 2;
                } elseif (($request->uniteid == 1) && ($request->equipeid == 48)) {
                    $unite_id = 7;
                    $equip_id = 3;
                } elseif (($request->uniteid == 1) && ($request->equipeid == 49)) {
                    $unite_id = 7;
                    $equip_id = 0;
                } elseif (($request->uniteid == 3) && ($request->equipeid == 64)) {
                    $unite_id = 7;
                    $equip_id = 1;
                } elseif (($request->uniteid == 3) && ($request->equipeid == 65)) {
                    $unite_id = 7;
                    $equip_id = 2;
                } elseif (($request->uniteid == 3) && ($request->equipeid == 66)) {
                    $unite_id = 7;
                    $equip_id = 3;
                } elseif (($request->uniteid == 3) && ($request->equipeid == 49)) {
                    $unite_id = 7;
                    $equip_id = 0;
                } elseif (($request->uniteid == 11) && ($request->equipeid == 49)) {
                    $unite_id = 9;
                    $equip_id = 0;
                }
                //dd($unite_id);

                //dd($travail->type_employer.$unite_id.''.$equip_id.$request->matri_quatre);
                //$travail->identifiant = 1.$unite_id.$equip_id.$request->matri_quatre;
                $travail->bulletin_modele_salarie = 2;
                $travail->type_salaire = 1;
            } elseif ($morceau == 'E') {

                $travail->date_debut_contrat = $request->date_embauche_contrat_new;
                $travail->date_fin_contrat = $request->date_fin_contrat_new;
                $travail->type_employer = 2;
                $travail->bulletin_modele_salarie = 1;
                $travail->type_salaire = 2;
            }
            /*if(){
				$travail->identifiant = $request->matri_quatre.
			}*/

            $travail->identifiant = $request->departementid;
            $travail->departementid = $request->departementid;
            $travail->nationaliteid = $request->paysid;
            $travail->telephone = null;
            $travail->telephone2 = null;
            $travail->description = $request->mot;
            $travail->niveau_etudeid = null;

            $travail->idtype_contrat = $request->idtype_contrat;
            $travail->savoirFaire = null;
            $travail->etapeid = $request->etapeid; //0 etape 1 , 1 etape 2
            $travail->inscrit_le = date('Y-m-d H-i-s');
            $travail->ip = $_SERVER['REMOTE_ADDR'];
            $travail->mois = date('m');
            $travail->annee = date('Y');
            $travail->save();

            if ($travail->save()) {

                // gestion des date embauche, cessassion
                $verif_action = ActionsCDC::where('actionid', 1)->where('travailleurid', $travail->id)->first();
                if ($verif_action) {
                    return Redirect::back()->withErrors("Ce journalier a déja ete embauché, veuillez effectuer sa cessation afin de le reconduire .");
                } else {
                    $actions = new ActionsCDC();
                    $actions->date_choisit = $travail->date_debut_contrat;
                    $actions->debut_contrat = $travail->date_debut_contrat;
                    $actions->fin_contrat = $travail->date_fin_contrat;
                    $actions->travailleurid = $travail->id;
                    $actions->travailleur_mat = $travail->matricule;
                    $actions->userid = Auth::user()->id;
                    $actions->actionid = 1; //1: embauché(contrat normal)
                    $actions->save();

                    if ($actions->save() == true) {

                        $unite = new HistoriqueUnite();;
                        $unite->travailleurid = $travail->id;
                        $unite->uniteid = $request->uniteid;
                        $unite->equipeid = $request->equipeid;
                        $unite->date_choix = $actions->debut_contrat;
                        $unite->userid = Auth::user()->id;
                        $unite->updated_at = Carbon::now();
                        $unite->save();
                    }
                }

                $insert_data_final[] = array(
                    'matricule'  => $request->matricule,
                    'element'   => 225,
                    'code'   => 'HA01',
                    'valeur'    => 0,
                    'statutid'    => 2,
                    'dateha'  => date('Y-m-d'),
                    'userid'   => Auth::user()->id,
                );

                DB::table('e_ha01_final')->insert($insert_data_final);

                $insert_ha01[] = array(
                    'matricule'  => $request->matricule,
                    'element'   => 225,
                    'code'   => 'HA01',
                    'valeur_ha01'   => 0,
                    'variable'   => 0,
                    'statutid'    => 1,
                    'dateha'  => date('Y-m-d'),
                    'userid'   => Auth::user()->id,
                );

                DB::table('e_ha01')->insert($insert_ha01);

                if (($request->etapeid == 1) && ($actions->save())) {

                    return Redirect::route('etapedeuxtravailleur', $travail->id)->withSuccess("Vous etes a la 2iem étape de l'enregistrement du travailleur : " . strtoupper($request->nom . ' ' . $request->prenom) . " .");
                } elseif ($request->etapeid == 0) {
                    return Redirect::back()->withSuccess("Vous avez terminé avec succès la première étape de l'enregistrement du travailleur.");
                }
            }
        }
    }

    public function post_travailleur_autres(Request $request)
    {

        $verif_mat = Travailleur::where('matricule', strtoupper($request->matricule))->first();

        if ($verif_mat) {
            return Redirect::back()->withErrors("Désoler ce matricule a été deja utilisé.");
        } else {

            $chaine = $request->matricule;
            $morceau = substr($chaine, 0, 1);

            $datefincontrat = date("Y-m-d", strtotime("$request->dateembauche +330 day"));
            $tabDate = explode("-", $request->dateembauche);
            $travail = new Travailleur();
            $travail->matricule = strtoupper($request->matricule);
            $travail->civilite = NULL;
            $travail->nom = strtoupper($request->nom);
            $travail->prenom = strtoupper($request->prenom);
            $travail->date_naissance = null;
            $travail->numero_securite = Null;
            $travail->date_fin_contrat = null;
            $travail->date_debut_contrat = null;
            $travail->situation_mat = $request->situation_mat;
            $travail->nombre_enfant = null;
            $travail->uniteid = $request->uniteid;
            $travail->categorieid = null;
            $travail->fonction_entrepriseid = $request->fonction_entrepriseid;
            $travail->email = null;
            $travail->statutid = 4; // pour les etrangers
            $travail->userid = Auth::user()->id; // enregistrer par

            if ($morceau == 'J') {
                $travail->type_employer = 1;
                $travail->bulletin_modele_salarie = 2;
                $travail->type_salaire = 1;
            } elseif ($morceau == 'E') {
                $travail->type_employer = 2;
                $travail->bulletin_modele_salarie = 1;
                $travail->type_salaire = 2;
            }

            $travail->departementid = $request->departementid;
            $travail->equipeid = null;
            $travail->nationaliteid = $request->paysid;
            $travail->telephone = null;
            $travail->telephone2 = null;
            $travail->description = null;
            $travail->niveau_etudeid = null;
            $travail->savoirFaire = null;
            $travail->etapeid = $request->etapeid; //0 etape 1 , 1 etape 2
            $travail->inscrit_le = date('Y-m-d H-i-s');
            $travail->ip = $_SERVER['REMOTE_ADDR'];
            $travail->mois = date('m');
            $travail->annee = date('Y');

            // Sauvegarder d'abord pour obtenir l'ID et le matricule
            $travail->save();

            // Gestion de l'upload de la photo après le premier save
            if ($request->hasFile('photo')) {
                try {
                    $photo = $request->file('photo');
                    $photoName = time() . '_' . $travail->matricule . '.' . $photo->getClientOriginalExtension();
                    $destinationPath = public_path('../rhassets/images/travailleurs');

                    // Créer le dossier s'il n'existe pas
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $photo->move($destinationPath, $photoName);
                    $travail->photo = $photoName;
                    $travail->save();
                } catch (\Exception $e) {
                    // Log l'erreur mais continue l'enregistrement
                    \Log::error('Erreur upload photo: ' . $e->getMessage());
                }
            }

            return Redirect::back()->withSuccess("Travailleur: " . strtoupper($request->nom . ' ' . $request->prenom) . " enregistré avec succès .");
        }
    }

    public function post_edit_travailleur($id, Request $request)
    {
        //dd($request->paysid);
        /* $cmpte = Travailleur::where('matricule', '!=', $request->matricule)->first();
        dd('okkkkk');
        if($cmpte){
            return Redirect::back()->withErrors("Désoler le matricule existe deja dans la base de donnée, veuillez modifier a nouveau.");
        } else{*/

        //$datefincontrat = date( "Y-m-d", strtotime( "$request->dateembauche +330 day" ) );
        $chaine = $request->matricule;
        $morceau = substr($chaine, 0, 1);

        // prenoms pr sage
        $nbre_car = strlen($request->prenom);
        $prenom_un = substr(strtoupper($request->prenom), 0, 19);
        $prenom_deux = substr(strtoupper($request->prenom), 19, $nbre_car);

        $travail = Travailleur::find($id);
        $travail->matricule = $request->matricule;
        $travail->civilite = $request->civilite;
        $travail->nom = strtoupper($request->nom);
        $travail->prenom = $prenom_un;

        if ($prenom_deux == false) {
            $travail->prenom_suite = null;
        } else {
            $travail->prenom_suite = $prenom_deux;
        }

        $travail->date_naissance = $request->datenaissance;
        $travail->numero_securite = $request->numero_securite;

        if ($morceau == 'J') {
            $travail->date_debut_contrat = $request->dateembauche;
            $travail->date_fin_contrat = $request->datefincontrat;
        } elseif ($morceau == 'E') {
            $travail->date_debut_contrat = $request->dateembauche;
            $travail->date_fin_contrat = $request->datefincontrat;
        }

        $travail->situation_mat = ($request->situation_mat);
        $travail->nombre_enfant = $request->nombre_enfant;
        $travail->lieu_naissance = $request->lieunaissance;
        $travail->uniteid = $request->uniteid;
        $travail->categorieid = $request->categorieid;
        $travail->fonction_entrepriseid = $request->fonction_entrepriseid;
        $travail->email = $request->email;
        $travail->statutid = 2; // enregistrer etape deux
        $travail->userid = Auth::user()->id; // enregistrer par
        $travail->type_employer = $request->typeEmployer; //
        $travail->departementid = $request->departementid;
        $travail->idtype_contrat = $request->idtype_contrat;
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

        /*if($travail->etapeid == 1){
                $travail->etapeid = 2;
            }else{

            }*/

        $travail->etapeid = 2; // fin enregistrement contrat telecharger
        $travail->inscrit_le = date('Y-m-d H-i-s');
        $travail->ip = $_SERVER['REMOTE_ADDR'];

        // Gestion de l'upload de la photo
        if ($request->hasFile('photo')) {
            try {
                // Supprimer l'ancienne photo si elle existe et n'est pas la photo par défaut
                if ($travail->photo && $travail->photo !== 'default.png') {
                    $oldPhotoPath = public_path('../rhassets/images/travailleurs/' . $travail->photo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $photo = $request->file('photo');
                $photoName = time() . '_' . $travail->matricule . '.' . $photo->getClientOriginalExtension();
                $destinationPath = public_path('../rhassets/images/travailleurs');

                // Créer le dossier s'il n'existe pas
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $photo->move($destinationPath, $photoName);
                $travail->photo = $photoName;
            } catch (\Exception $e) {
                // Log l'erreur mais continue l'enregistrement
                \Log::error('Erreur upload photo: ' . $e->getMessage());
            }
        }

        $travail->save();

        if ($travail->etapeid == 2) {
            return Redirect::route('lientelechargerContrat', $id)->withSuccess("Fin de l'enregistrement du travailleur : " . strtoupper($request->nom . ' ' . $request->prenom) . " Vous pouvez télécharger son contrat .");
        } else {
            return Redirect::back()->withSuccess("La modification a été effectuée avec succès.");
        }

        //}

    }

    public function delete_photo_travailleur($id)
    {
        $travailleur = Travailleur::findOrFail($id);

        // Vérifier si le travailleur a une photo
        if ($travailleur->photo && $travailleur->photo !== 'default.png') {
            $photoPath = public_path('../rhassets/images/travailleurs/' . $travailleur->photo);

            // Supprimer le fichier physique s'il existe
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }

            // Mettre à NULL dans la base de données
            $travailleur->photo = null;
            $travailleur->save();

            return Redirect::back()->withSuccess("La photo a été supprimée avec succès.");
        }

        return Redirect::back()->withErrors("Aucune photo à supprimer.");
    }

    public function edit_travailleur($id, Request $request)
    {

        /* $cmpte = Travailleur::where('matricule', '!=', $request->matricule)->first();
        dd('okkkkk');
        if($cmpte){
            return Redirect::back()->withErrors("Désoler le matricule existe deja dans la base de donnée, veuillez modifier a nouveau.");
        } else{*/

        $datefincontrat = date("Y-m-d", strtotime("$request->dateembauche +330 day"));
        $chaine = $request->matricule;
        $morceau = substr($chaine, 0, 1);

        // prenoms pr sage
        $nbre_car = strlen($request->prenom);
        $prenom_un = substr(strtoupper($request->prenom), 0, 19);
        $prenom_deux = substr(strtoupper($request->prenom), 19, $nbre_car);

        $travail = Travailleur::find($id);
        $travail->matricule = $request->matricule;
        $travail->civilite = $request->civilite;
        $travail->nom = $request->nom;
        $travail->prenom = $prenom_un;
        $travail->prenom_suite = $prenom_deux;
        $travail->date_naissance = $request->datenaissance;
        $travail->numero_securite = $request->numero_securite;
        $travail->date_debut_contrat = $request->dateembauche;

        if ($morceau == 'J') {
            $travail->date_fin_contrat = $datefincontrat;
        } elseif ($morceau == 'E') {
            $travail->date_fin_contrat = $request->date_fin_contrat;
        }

        $travail->situation_mat = ($request->situation_mat);
        $travail->nombre_enfant = $request->nombre_enfant;
        $travail->lieu_naissance = $request->lieunaissance;
        $travail->uniteid = $request->uniteid;
        $travail->categorieid = $request->categorieid;
        $travail->idtype_contrat = $request->idtype_contrat;
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

        if ($travail->save()) {

            return Redirect::back()->withSuccess("La modification a été effectuée avec succès.");
        }

        //}

    }

    public function chager_etat($id)
    {

        $editTenue = Tenues::find($id);

        if ($editTenue->etat == 1) {
            $editTenue->etat = 2; // etat mauvais
            $editTenue->userid = Auth::user()?->id; // etat
            $editTenue->save();
            if ($editTenue->save()) {
                return Redirect::back()->withErrors("Le statut de la tenue du travailleur a été modifié avec succès.");
            }
        } elseif ($editTenue->etat == 2) {
            $editTenue->etat = 1; // etat bon
            $editTenue->userid = Auth::user()?->id; // etat
            $editTenue->save();
            if ($editTenue->save()) {
                return Redirect::back()->withSuccess("Le statut de la tenue du travailleur a été modifié avec succès.");
            }
        }
    }

    public function getEvenementsCalendrier()
    {
        $autorisations = Autorisations::with('travailleur')->get();
        $evenements = [];

        foreach ($autorisations as $autorisation) {
            $motif = '';
            switch ($autorisation->motif_absence) {
                case 1:
                    $motif = 'MALADIE';
                    break;
                case 2:
                    $motif = 'CONVENANCE PERSONNELLE';
                    break;
                case 3:
                    $motif = 'PERMISSIONS EXCEPTIONNELLES';
                    break;
                case 4:
                    $motif = 'CONGES PAYES';
                    break;
                case 5:
                    $motif = 'CONGES SANS SOLDE';
                    break;
                case 6:
                    $motif = 'MISSION';
                    break;
                default:
                    $motif = 'AUTRES CAS';
                    break;
            }

            $evenements[] = [
                'title' => ($autorisation->travailleur?->nom ?? 'N/A') . ' : ' . $motif,
                'start' => $autorisation->date_debut,
                'end' => $autorisation->date_fin,
            ];
        }

        return response()->json($evenements);
    }

    public function calendrier_conges()
    {
        return view('calendrier.index');
    }

    public function etapedeuxtravailleur($id)
    {
        $edit = Travailleur::findOrFail($id);
        $departements = Departement::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();
        $equipes = Equipes::orderBy('id', 'DESC')->get();
        $pays = Pays::orderBy('id', 'DESC')->get();
        $fonctions = Fonction::orderBy('id', 'DESC')->get();
        $commune = Commune::orderBy('id', 'DESC')->get();
        $categories = Categories::orderBy('id', 'DESC')->get();
        $niveauEtudes = NiveauEtude::orderBy('id', 'DESC')->get();
        $data_typecontrat = \App\TypeContrat::orderBy('id', 'DESC')->get();

        return view('travailleur.edit', compact(
            'edit',
            'departements',
            'unites',
            'equipes',
            'pays',
            'fonctions',
            'commune',
            'categories',
            'niveauEtudes',
            'data_typecontrat'
        ));
    }

    public function lientelechargerContrat($idtravailleur)
    {

        $departements = Departement::orderBy('id', 'DESC')->get();
        $unites = Unites::orderBy('id', 'DESC')->get();
        $pays = Pays::orderBy('id', 'DESC')->get();
        $equipes = Equipes::orderBy('id', 'DESC')->get();
        $edit_travailleur = Travailleur::where('id', $idtravailleur)->first();

        return view("travailleur.contrat", compact('idtravailleur', 'edit_travailleur', 'pays', 'unites', 'departements', 'equipes'));
    }

    public function telechargerContratJournalier(Request $request, $id)
    {
        $travailleur = Travailleur::find($id);

        // Vérification correcte des pièces d'identité
        if (
            is_null($travailleur->pieceidentite) ||
            is_null($travailleur->pieceidentite_livrele) ||
            is_null($travailleur->pieceidentite_lieu)
        ) {
            return Redirect::back()->withErrors("Les informations de la pièce d'identité ne sont pas renseignées. Veuillez contacter le travailleur.");
        }

        // Récupération des données
        $departements = Departement::find($travailleur->departementid);
        $unites       = Unites::find($travailleur->uniteid);
        $pays         = Pays::find($travailleur->nationaliteid);
        $equipes      = Equipes::find($travailleur->equipeid);

        // Génération PDF
        if ($request->has('download')) {

            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            $pdf = PDF::loadView(
                'contrat.contrat_journalier',
                compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays')
            );

            return $pdf->download("contrat_journalier-$travailleur->nom-$travailleur->prenom.pdf");
        }
    }


    public function telechargerContratCDD(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();
        if (($travailleur->pieceidentite == "NULL") || ($travailleur->pieceidentite_livrele == "NULL") || ($travailleur->pieceidentite_lieu == "NULL")) {
            return Redirect::back()->withErrors("Les informations de la piece d'identité ne sont pas renseigné, Veuillez contacter le travailleur svp.");
        } else {
            $departements = Departement::where('id', $travailleur->departementid)->first();
            $unites = Unites::where('id', $travailleur->uniteid)->first();
            $pays = Pays::where('id', $travailleur->paysid)->first();
            $equipes = Equipes::where('id', $travailleur->equipeid)->first();

            if ($request->has('download')) {
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                $pdf = PDF::loadView('contrat.contrat_cdd', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
                return $pdf->download("contrat_cdd-$travailleur->nom-$travailleur->prenom.pdf");
            }
        }
    }

    public function telechargerContratCDI(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();
        if (($travailleur->pieceidentite == "NULL") || ($travailleur->pieceidentite_livrele == "NULL") || ($travailleur->pieceidentite_lieu == "NULL")) {
            return Redirect::back()->withErrors("Les informations de la piece d'identité ne sont pas renseigné, Veuillez contacter le travailleur svp.");
        } else {
            $departements = Departement::where('id', $travailleur->departementid)->first();
            $unites = Unites::where('id', $travailleur->uniteid)->first();
            $pays = Pays::where('id', $travailleur->paysid)->first();
            $equipes = Equipes::where('id', $travailleur->equipeid)->first();

            if ($request->has('download')) {
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                $pdf = PDF::loadView('contrat.contrat_cdi', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
                return $pdf->download("contrat_cdi-$travailleur->nom-$travailleur->prenom.pdf");
            }
        }
    }

    public function telechargerContratCessassion(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();
        //dd($travailleur->pieceidentite);
        if (($travailleur->pieceidentite == "NULL") || ($travailleur->pieceidentite_livrele == "NULL") || ($travailleur->pieceidentite_lieu == "NULL")) {
            return Redirect::back()->withErrors("Les informations de la piece d'identité ne sont pas renseigné, Veuillez contacter le travailleur svp.");
        } else {

            $departements = Departement::where('id', $travailleur->departementid)->first();
            $unites = Unites::where('id', $travailleur->uniteid)->first();
            $pays = Pays::where('id', $travailleur->paysid)->first();
            $equipes = Equipes::where('id', $travailleur->equipeid)->first();

            if ($request->has('download')) {
                // Set extra option
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                // pass view file
                $pdf = PDF::loadView('contrat.contrat_cessassion', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
                // download pdf
                return $pdf->download("contrat_cessassion_$travailleur->matricule.pdf");
            }
        }
    }

    public function telechargerContratCertificatTravail(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if ($request->has('download')) {
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_certificat_travail', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("contrat_certificat_travail_$travailleur->matricule.pdf");
        }
    }

    public function telechargerSanctions(Request $request, $mat, $idsanc)
    {
        $sanction = Sanctions::where('id', $idsanc)->first();
        $frdate = Carbon::parse($sanction->datefautes)->formatLocalized('%d %b %Y');

        $listesanctiones = unserialize($sanction->employeid);
        $travailleur = Travailleur::where('matricule', $mat)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();
        $id = $idsanc;

        if ($request->has('download')) {
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('sanctions.download', compact('id', 'sanction', 'frdate', 'travailleur', 'departements', 'listesanctiones', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("fiche_sanction_$mat.pdf");
        }
    }

    public function telechargerContratDeclarationCnps(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $departements = Departement::where('id', $travailleur->departementid)->first();
        $unites = Unites::where('id', $travailleur->uniteid)->first();
        $pays = Pays::where('id', $travailleur->paysid)->first();
        $equipes = Equipes::where('id', $travailleur->equipeid)->first();

        if ($request->has('download')) {
            // Set extra option
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadView('contrat.contrat_declaration', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
            // download pdf
            return $pdf->download("contrat_declaration_cnps_$travailleur->matricule.pdf");
        }
    }

    public  function post_precarite_finalite()
    {

        $verif_ha01 = HAO1::where('statutid', 2)->get();

        if ($verif_ha01) {

            $verif_Precarites = Precarites::where('statutid', 2)->get();

            if ($verif_Precarites->count() > 0) {

                foreach ($verif_ha01 as $verif1) {

                    foreach ($verif_Precarites as $pluspreca) {

                        if ($verif1->matricule == $pluspreca->matricule) {

                            $hao1_Precarites = Precarites::where('matricule', $pluspreca->matricule)->first();
                            $update_Precarites = Precarites::find($hao1_Precarites->id);
                            $update_Precarites->valeur += intval($verif1->valeur_ha01);
                            $update_Precarites->userid = Auth::user()->id;
                            $update_Precarites->updated_at = Carbon::now();
                            $update_Precarites->save();
                        }
                    }
                }

                foreach ($verif_ha01 as $ha01) {

                    $update_H0A1_del = HAO1::find($ha01->id);
                    $update_H0A1_del->valeur_ha01 = 0;
                    $update_H0A1_del->variable = 0;
                    $update_H0A1_del->statutid = 1;
                    $update_H0A1_del->userid = Auth::user()->id;
                    $update_H0A1_del->save();
                }

                return Redirect::back()->withSuccess("Précarités ajoutés avec succès.");
            } elseif ($verif_Precarites->count() == 0) {

                // pas de preca enregistrer
                foreach ($verif_ha01 as $ha01) {
                    $insert[] =
                        [
                            'matricule' => $ha01->matricule,
                            'element' => intval('255'),
                            'code' => 'HA01',
                            'valeur' => $ha01->valeur_ha01,
                            'dateha' => date('Y-m-d'),
                            'statutid' => 2, // finaliser
                            'userid' => Auth::user()->id, // concerner
                        ];
                }
                DB::table('e_ha01_final')->insert($insert);

                foreach ($verif_ha01 as $ha01) {

                    $update_H0A1_del = HAO1::find($ha01->id);
                    $update_H0A1_del->valeur_ha01 = 0;
                    $update_H0A1_del->variable = 0;
                    $update_H0A1_del->statutid = 1;
                    $update_H0A1_del->userid = Auth::user()->id;
                    $update_H0A1_del->save();
                }

                return Redirect::back()->withSuccess("Précarités ajoutés avec succès.");
            }
        } else {
            return Redirect::back()->withErrors("Impossible.");
        }
    }

    public function telechargerFichePrecarite(Request $request, $id)
    {
        $travailleur = Travailleur::where('id', $id)->first();

        $verif_First = Precarites::where('matricule', $travailleur->matricule)->first();

        if ($verif_First) {

            $departements = Departement::where('id', $travailleur->departementid)->first();
            $unites = Unites::where('id', $travailleur->uniteid)->first();
            $pays = Pays::where('id', $travailleur->paysid)->first();
            $equipes = Equipes::where('id', $travailleur->equipeid)->first();

            if ($request->has('download')) {
                // Set extra option
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                // pass view file
                $pdf = PDF::loadView('contrat.fiche_precarite', compact('id', 'travailleur', 'departements', 'equipes', 'unites', 'pays'));
                // download pdf
                return $pdf->download("fiche_precarite_$travailleur->matricule.pdf");
            }
        } else {
            return Redirect::back()->withErrors("Impossible, vous n'avez pas de précarité.");
        }
    }


    public function exels_precarites_debut()
    {

        $recherches_hoa1 = HAO1::where('statutid', 2)->get();

        $customer_array[] = array('Matricule', 'Element', 'CodeElement', 'Valeur HA01', 'Variables', 'Date');

        foreach ($recherches_hoa1 as $customer) {

            $customer_array[] = array(
                'Matricule'  => $customer->matricule,
                'Element'  => $customer->element,
                'CodeElement'  => $customer->code,
                'Valeur HA01'  => $customer->valeur_ha01,
                'Variables'  => $customer->variable,
                'Date'  => $customer->dateha,
            );
        }

        Excel::create('HA01 de la paie en cour', function ($excel) use ($customer_array) {
            $excel->setTitle('HA01 de la paie en cour');
            $excel->sheet('HA01 de la paie en cour', function ($sheet) use ($customer_array) {
                $sheet->fromArray($customer_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }


    public function exels_precarites($code)
    {
        if ($code) {

            $tabCodes = explode("+", $code);

            if (count($tabCodes)) {

                $recherches = Precarites::where('dateha', '>=', $tabCodes['0'])
                    ->where('dateha', '<=', $tabCodes['1'])
                    ->where('matricule', 'like', '%' . $tabCodes['2'] . '%')->get();

                $customer_array[] = array('Matricule', 'Element', 'CodeElement', 'Valeur');

                foreach ($recherches as $customer) {

                    $customer_array[] = array(
                        'Matricule'  => $customer->matricule,
                        'Element'  => $customer->element,
                        'CodeElement'  => $customer->code,
                        'Valeur'  => $customer->valeur,
                    );
                }
            }

            Excel::create('HA01 Nb de jours travaillés', function ($excel) use ($customer_array) {
                $excel->setTitle('HA01 Nb de jours travaillés');
                $excel->sheet('HA01 Nb de jours travaillés', function ($sheet) use ($customer_array) {
                    $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
            })->download('xlsx');
        }
    }
}
