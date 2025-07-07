<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('login'); });
Route::get('/se-connecter',['as'=>'login', 'uses'=>'HomeController@logining']);
Route::post('post_login', 'HomeController@post_login');

Route::group(array('before' => 'Auth', 'middleware' => 'auth'), function() {

        Route::get('/se-deconnecter/{id}',['as'=>'logoutUser', 'uses'=>'HomeController@logoutUser']);

        Route::get('/bienvenue', function () {
            $datejour = date('Y-m-d');
            $datefincontrat = date("Y-m-d", strtotime("$datejour +20 day" ));
            //dd($datefincontrat);
            $count_fin_contrat = \App\Travailleur::where('etapeid', '!=' ,3)->where('etapeid', '!=' ,4)->where('date_fin_contrat', '<=', $datefincontrat)->where('date_fin_contrat', '>', $datejour)->get();
            $count_journaler_non_declare = \App\Travailleur::where('etapeid', '!=' ,3)->where('numero_securite', '!=' ,NULL)->where('etapeid', '!=' ,4)->where('date_fin_contrat', '<=', $datefincontrat)->where('date_fin_contrat', '>', $datejour)->get();
            //dd($count_fin_contrat);
            $termJ = 'J';
            $termE = 'E'; 
			$listeAT = \App\AccidentTravail::where('statutid', '=' ,1)->orderBy('id', 'DESC')->count();
            $data_journalier = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->where('equipeid', '!=' ,NULL)->where('etapeid', '!=' ,3)->count();
            $data_sanctions = \App\Sanctions::where('mois',  date('m'))->count();
            $data_variables = \App\Variables::where('mois',  date('m'))->count();
            $data_autorisation = \App\Autorisations::where('mois',  date('m'))->count();
            $data_precarite = \App\Precarites::where('statutid',  2)->get();
            $data_embauche = \App\Travailleur::where('matricule', 'like', '%' . $termE . '%')->count();
            $data_avec_arret = \App\Santes::where('mois', date('m'))->where('arret_travail', '1')->count();
            $data_sans_arret = \App\Santes::where('mois', date('m'))->where('debut_arret', null)->count();
            $data_travailleur_moisencour = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->where('mois', date('m'))->where('annee', date('Y'))->count();
            $article = \App\ArticleRecu::where('quantite_en_stock', '<', 50)->limit(5)->get();
            return view('home.content', compact('count_fin_contrat', 'count_journaler_non_declare', 'listeAT', 'data_avec_arret', 'data_sans_arret', 'data_precarite', 'data_autorisation', 'data_variables', 'data_sanctions', 'article', 'data_embauche', 'data_journalier', 'data_travailleur_moisencour'));
        });

        Route::get('/monprofil/{id}', function () { return view('home.profil'); });
        Route::get('/erh/recrutement', function () { return view('menu.recrutement.home'); });

        /******************************* RECRUTEMENT  *******************************/
        Route::get('/inscription-ouvrier', function () { return view('menu.recrutement.inscription'); });
        Route::get('/liste-complte-travailleur', function () { return view('menu.recrutement.listecomplete'); });

        /******************** LOGIN  *********************/

        /******************************* TRAVAILLEUR  *******************************/

        Route::get('/liste-embauches', function () {
            $termJ = 'E';
            $data_unites = \App\Unites::get();
            $data_equipes = \App\Equipes::get();
            $data_departements = \App\Departement::get();
            $data_pays = \App\Pays::get();
            $data_journalier = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->get();
            return view('travailleur.liste_embauches', compact('data_journalier', 'data_pays', 'data_unites', 'data_equipes', 'data_departements'));
        });
        Route::get('/ajouter-autres-travailleur', function () {
            $termJ = 'J';
            $data_unites = \App\Unites::get();
            $data_equipes = \App\Equipes::get();
            $data_fonctions = \App\Fonction::get();
            $data_departements = \App\Departement::get();
            $data_pays = \App\Pays::get();
            $mat_journalier = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->get()->limit(10);
			dd($data_journalier);
			$chiffre = substr($data_journalier->matricule, 4);
            return view('travailleur.addautres', compact('data_journalier', 'data_fonctions', 'data_pays', 'data_unites', 'data_equipes', 'data_departements'));
        });
        Route::get('/ajouter-travailleur-etape-un', function () {
            $termJ = 'J';
            $data_unites = \App\Unites::get();
            $data_typecontrat = \App\TypeContrat::get();
            $data_equipes = \App\Equipes::get();
            $data_departements = \App\Departement::get();
            $data_pays = \App\Pays::get();
            $data_journalier = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->latest('id')->first();
			
			$mat_journalier = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->orderBy('id', 'DESC')->limit(10)->get();
			
			foreach($mat_journalier as $jour){
				if($data_journalier->matricule < $jour->matricule){
					$maj_big = substr($jour->matricule, 4);
				}else{
					$maj_big = substr($data_journalier->matricule, 4);
				}
			}
			
            return view('travailleur.add', compact('data_journalier', 'maj_big', 'data_typecontrat', 'data_pays', 'data_unites', 'data_equipes', 'data_departements'));
        });
        Route::get('/ajouter-travailleur-etape-deux/{slug}', function () { 
		return view('travailleur.edit'); 
		});
        Route::get('/telecharger-contrat/{slug}', function () { return view('travailleur.contrat'); });

        //Route::get('/declaration-travailleurs',['as'=>'declaration', 'uses'=>'RecruController@declaration']);
        Route::get('action/declaration/travailleurs', 'RecruController@declaration');
        Route::post('post_search', 'RecruController@post_search');
        Route::post('post_search_varaiables', 'RecruController@post_search_varaiables');
        Route::get('reconduire/journalier', 'RecruController@reconduireJournalier');
        Route::get('/action-telecharger-contrat/{id}',['as'=>'actionsContrat', 'uses'=>'RecruController@actionsContrat']);
        Route::get('/telecharger-exel-travailleurs/{code}',['as'=>'excel_download', 'uses'=>'RecruController@excel_download']);
        Route::get('/telecharger-quinzaine-/{code}',['as'=>'excel_download_quinzaine', 'uses'=>'RecruController@excel_download_quinzaine']);
        Route::get('/telecharger-exel-travailleurs-fin-contrat',['as'=>'excel_download_fin_contrat', 'uses'=>'RecruController@excel_download_fin_contrat']);
        Route::get('/telecharger-exel-variables-travailleurs/{code}',['as'=>'excel_download_variables', 'uses'=>'RecruController@excel_download_variables']);
        Route::get('/travailleurs-contrat-cessations',['as'=>'liste_cessations', 'uses'=>'RecruController@liste_cessations']);
        Route::get('/travailleurs-contrat-declarations-cnps',['as'=>'liste_declarations', 'uses'=>'RecruController@liste_declarations']);
        Route::post('update/declarations/updatedeclaration', 'RecruController@updatedeclaration');
        Route::post('update/reconduire/uptravailleur', 'RecruController@updatereconduire');
        Route::get('/liste-travailleurs-etapes-deux',['as'=>'listetravailleurs', 'uses'=>'RecruController@listetravailleurs']);
        Route::get('/liste-tous-les-travailleurs',['as'=>'liste_tous_travailleurs', 'uses'=>'RecruController@liste_tous_travailleurs']);
        Route::get('/tous-les-travailleurs',['as'=>'liste_travailleurs', 'uses'=>'RecruController@liste_travailleurs']);
        Route::get('/liste-certificat-de-travails',['as'=>'liste_certificat_travail', 'uses'=>'RecruController@liste_certificat_travail']);
        Route::get('/historiques-travailleurs',['as'=>'historiques', 'uses'=>'RecruController@historique']);
        Route::get('/patient-reçu/{id}',['as'=>'patientrexu', 'uses'=>'RecruController@patientrexu']);
        Route::get('/variables-santes/{id}',['as'=>'variables_sante', 'uses'=>'SanctionController@variables_sante']);
        Route::get('/historiques-contrat/{id}',['as'=>'historiques_contrat', 'uses'=>'RecruController@historiques_contrat']);

        /** MISSIONS */
        Route::get('/journaliers-fin-contrat', function () {
            $datejour = date('Y-m-d');
            $termE = 'J';
            $datefincontrat = date("Y-m-d", strtotime("$datejour +20 day" ));
            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,6)->where('matricule', 'like', '%' . $termE . '%')->where('date_fin_contrat', '<=', $datefincontrat)->where('date_fin_contrat', '>', $datejour)->get();
            return view('travailleur.listejournalierfin_contrat', compact('data_travailleur'));
        });
        Route::get('/ajouter-autorisation', function () {
            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->orderBy('id', 'DESC')->get();
            return view('autorisations.add', compact('data_travailleur'));
        });
        Route::get('/liste-autorisations',['as'=>'listeautorisations', 'uses'=>'EmployerController@listeautorisations']);
        Route::get('/liste-missions',['as'=>'listemissions', 'uses'=>'EmployerController@listemissions']);
        Route::get('/calenajouter-ha01drier-calendrier',['as'=>'calendrier_conges', 'uses'=>'EmployerController@calendrier_conges']);
        Route::get('/calenajouter-ha01drier-calendrier',['as'=>'calendrier_conges', 'uses'=>'EmployerController@calendrier_conges']);

        /** CONGES */
        Route::get('/ajouter-conges', function () { return view('menu.conges.ajouter'); });
        Route::get('/liste-conges',['as'=>'listeconges', 'uses'=>'RecruController@listeconges']);

        /** ¨PRECARITE */
        Route::get('/ajouter-ha01', function () {
            $code = '';
            $data_hao1 = \App\HAO1::orderBy('id', 'DESC')->get();
            $verif_traitement_ha01 = \App\HAO1::where('statutid', 2)->count();
            return view('precarite.add', compact('data_hao1', 'verif_traitement_ha01', $code));
        });
        Route::get('/historique-ha01',['as'=>'historique_ha01', 'uses'=>'EmployerController@historique_ha01']);
        Route::get('/finaliser-ha01',['as'=>'finaliser_ha01', 'uses'=>'EmployerController@finaliser_ha01']);
        Route::get('/exporter-precarites-exels/{code}',['as'=>'exels_precarites', 'uses'=>'EmployerController@exels_precarites']);
        Route::get('/exporter-precarites-avant-finaliser',['as'=>'exels_precarites_debut', 'uses'=>'EmployerController@exels_precarites_debut']);
        Route::post('post_precarite_ho', 'EmployerController@post_precarite_ho');
        Route::post('post_precarite_finalite', 'EmployerController@post_precarite_finalite');
        Route::post('post_search_histo_precarite', 'EmployerController@post_search_histo_precarite');


    /** VARIABLES */
        Route::get('/variables', function () {
            return view('variables.variables', compact('data_travailleur'));
        });
        Route::get('/ajouter-variable', function () {
            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,3)->orderBy('id', 'DESC')->get();
            return view('variables.add', compact('data_travailleur'));
        });
        Route::get('/ajouter-heure-supplementaire', function () {
            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->orderBy('id', 'DESC')->get();
            $variableHS = \App\Variables::Where('cause', 2)->orderBy('id', 'DESC')->get();
            return view('variables.heure_sup', compact('data_travailleur', 'variableHS'));
        });
        Route::get('/ajouter-autres-variables', function () {
            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->orderBy('id', 'DESC')->get();
            $variableHS = \App\Variables::Where('cause', 2)->orderBy('id', 'DESC')->get();
            return view('variables.autres_variables', compact('data_travailleur', 'variableHS'));
        });
        Route::post('post_variables_manuelle', 'VariablesController@post_variables_manuelle');
        Route::post('post_autres_variables', 'VariablesController@post_autres_variables');
        Route::post('post_variables_heure_sup', 'VariablesController@post_variables_heure_sup');
        Route::get('/precarite-calcules',['as'=>'precarite_calc', 'uses'=>'EmployerController@precarite_calc']);
        Route::get('/liste-variables-automatique',['as'=>'listevariables_automatique', 'uses'=>'VariablesController@listevariables_automatique']);
        Route::get('/liste-variables-manuelles',['as'=>'listevariables_manuelle', 'uses'=>'VariablesController@listevariables_manuelle']);
        Route::get('/liste-variables-heure-supplementaire',['as'=>'listevariables_heure_supp', 'uses'=>'VariablesController@listevariables_heure_supp']);
        Route::get('/liste-variables-autres-variables',['as'=>'listevariables_autres_variables', 'uses'=>'VariablesController@listevariables_autres_variables']);
        Route::get('/historiques-variables',['as'=>'historiques_variables', 'uses'=>'EmployerController@historiques_variables']);
        Route::get('/liste-precarites',['as'=>'listeprecarites', 'uses'=>'EmployerController@listeprecarites']);

        /** SANCTION */
        Route::get('/ajouter-sanction', function () {
            $termE = 'E';
            $data_travailleur = \App\Travailleur::where('matricule', 'like', '%' . $termE . '%')->where('equipeid', '!=' ,NULL)->where('etapeid', '!=' ,3)->orderBy('id', 'DESC')->get();
            $data_travailleurJour = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->where('equipeid', '!=' ,NULL)->orderBy('id', 'DESC')->get();
            return view('sanctions.add', compact('data_travailleur', 'data_travailleurJour'));
        });
        Route::get('/sanctions-autres-travailleur', function () {
            $data_travailleur = \App\Travailleur::where('statutid',4)->orderBy('id', 'DESC')->get();
            $data_travailleurJour = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->orderBy('id', 'DESC')->get();
            return view('sanctions.addautres_sanction', compact('data_travailleur', 'data_travailleurJour'));
        });
        Route::get('/modifier-sanction', function () {

            $data_travailleur = \App\Travailleur::where('etapeid', '!=' ,3)->where('statutid', '!=' ,4)->orderBy('id', 'DESC')->get();
            return view('sanctions.edit', compact('data_travailleur'));
        });
        Route::get('/liste-sanction',['as'=>'listesanctions', 'uses'=>'SanctionController@listes_anctions']);
        Route::get('/telecharger-sanction/{id}',['as'=>'techarger_sanctions', 'uses'=>'SanctionController@techarger_sanctions']);
        Route::get('/sanction-variable/{id}',['as'=>'sanctionvariable', 'uses'=>'SanctionController@sanctionvariable']);
        Route::get('/add-autorisation-variables/{id}',['as'=>'autorisationvariable', 'uses'=>'SanctionController@autorisationvariable']);
        Route::get('/add-mission-variables/{id}',['as'=>'missionvariable', 'uses'=>'SanctionController@missionvariable']);
        Route::post('post_sanction', 'SanctionController@post_sanction');
        Route::post('post_autorisation', 'SanctionController@post_autorisation');

        /** SANTE */
        Route::get('santes', 'SanteController@index_santes')->name('santes');
        Route::get('ajouter-accident-travail', 'SanteController@accident_travail')->name('accidentTravail');
        Route::post('post_sante', 'SanteController@post_sante');
        Route::post('post_accident_travail', 'SanteController@post_accident_travail');
        Route::post('post_historiques_sante', 'SanteController@post_historiques_sante');
        Route::post('add/santes', 'SanteController@addsantes');
        Route::post('update/santes/updatesantes', 'SanteController@updatesantes');
        Route::get('edit/santes/data', 'SanteController@editsantes');
        Route::get('/ajouter-consultation', function () { return view('menu.sante.consultation'); });
        Route::get('/liste-accident-travail',['as'=>'listesaccident', 'uses'=>'SanteController@listesaccident']);
        Route::get('/liste-consultations',['as'=>'listesconsultation', 'uses'=>'SanteController@listesconsultation']);
        Route::get('/historique-consultations',['as'=>'historique_consultation', 'uses'=>'SanteController@historique_consultation']);
        Route::get('/accident-travail-traiter/{id}',['as'=>'accident_travail_traiter', 'uses'=>'SanteController@accident_travail_traiter']);
		
        Route::get('/detecter-matricule',['as'=>'detect_matricul', 'uses'=>'EmployerController@detect_matricul']);

        /** TENUE */
        Route::get('/ajouter-tenue', function () {
            $termJ = 'J';
            $data_travailleur = \App\Travailleur::where('matricule', 'like', '%' . $termJ . '%')->where('etapeid', '!=' ,3)->orderBy('id', 'DESC')->get();
            $data_ArticleRecu = \App\ArticleRecu::orderBy('id', 'DESC')->get();
            $data_Services = \App\Services_tenue::orderBy('id', 'DESC')->get();
            return view('tenues.add', compact('data_travailleur', 'data_Services','data_ArticleRecu'));
        });
        Route::get('chager_etat/{id}', 'EmployerController@chager_etat');
        Route::post('post_gestion_tenue', 'EmployerController@post_gestion_tenue');
        Route::post('post_appro_stock', 'EmployerController@post_appro_stock');
        Route::get('/liste-tenues',['as'=>'listetenues', 'uses'=>'EmployerController@listetenues']);
        Route::get('/stock-tenues',['as'=>'stock_tenues', 'uses'=>'EmployerController@stock_tenues']);
        Route::get('/approvisionner-stock-tenues',['as'=>'appro_stock', 'uses'=>'EmployerController@appro_stock']);

        Route::get('unites', 'ConfigController@index_unite')->name('unites');
        Route::get('unites/{id}', 'ConfigController@show_unite')->name('unites.show');

        Route::get('departements', 'ConfigController@index')->name('departements');
        Route::post('add/departements/adddepartements', 'ConfigController@adddepartements');
        Route::post('update/departements/updatedepartements', 'ConfigController@updatedepartements');
        Route::get('edit/departements/data', 'ConfigController@editdepartementsurl');
        Route::get('delete/departements/data', 'ConfigController@deletedepartements');
        Route::get('departements/{id}', 'ConfigController@show')->name('departements.show');

        /*******************************  EQUIPE *******************************************/
        Route::get('equipes', 'ConfigController@index_equipes')->name('equipes');
        Route::get('equipes/{id}', 'ConfigController@show_equipes')->name('equipes.show');
        Route::post('add/equipes/addequipes', 'ConfigController@addequipes');
        Route::post('update/equipes/updateequipes', 'ConfigController@updateequipes');
        Route::get('edit/equipes/data', 'ConfigController@editequipesurl');
        Route::get('delete/equipes/data', 'ConfigController@deleteequipes');

        /*******************************  UNITES *******************************************/

        Route::get('unites', 'ConfigController@index_unites')->name('unites');
        Route::get('unites/{id}', 'ConfigController@show_unites')->name('unites.show');
        Route::post('add/unites', 'ConfigController@addunites');
        Route::post('update/unites/updateunites', 'ConfigController@updateunites');
        Route::get('edit/unites/data', 'ConfigController@editunitessurl');
        Route::get('delete/unites/data', 'ConfigController@deleteunites');

        /*******************************  FONCTION *******************************************/

        Route::get('fonctions', 'ConfigController@index_fonction')->name('fonctions');
        Route::get('fonctions/{id}', 'ConfigController@show_fonctions')->name('fonctions.show');
        Route::post('add/fonctions/fonctions', 'ConfigController@addfonctions');
        Route::post('update/fonctions/updatefonctions', 'ConfigController@updatefonctions');
        Route::get('edit/fonctions/data', 'ConfigController@editfonctionsurl');
        Route::get('delete/fonctions/data', 'ConfigController@deletefonctions');

        /*******************************  NIVEAU ETUDE *******************************************/
        Route::get('categories', 'ConfigController@index_categories')->name('categories');
        Route::get('categories/{id}', 'ConfigController@show_categories')->name('categories.show');

        Route::post('add/categories/addcategories', 'ConfigController@addcategories');
        Route::post('update/categories/updatecategories', 'ConfigController@updatecategories');
        Route::get('edit/categories/data', 'ConfigController@editcategoriesurl');
        Route::get('delete/categories/data', 'ConfigController@deletecategories');

        Route::get('niveauEtude', 'ConfigController@index_niveauEtude')->name('niveauEtude');
        Route::get('niveauEtude/{id}', 'ConfigController@show_niveauEtude')->name('fonctions.show');

        Route::post('add/niveauEtude/addniveauEtude', 'ConfigController@addniveauEtude');
        Route::post('update/niveauEtude/updateniveauEtude', 'ConfigController@updateniveauEtude');
        Route::get('edit/niveauEtude/data', 'ConfigController@editniveauEtudeurl');
        Route::get('delete/niveauEtude/data', 'ConfigController@deleteniveauEtude');

        /*******************************  EQUIPE *******************************************/

        Route::get('pays', 'ConfigController@index_pays')->name('pays');
        Route::get('pays/{id}', 'ConfigController@show_pays')->name('pays.show');
        Route::post('add/pays/addpays', 'ConfigController@addpays');
        Route::post('update/pays/updatepays', 'ConfigController@updatepays');
        Route::get('edit/pays/data', 'ConfigController@editpaysurl');
        Route::get('delete/pays/data', 'ConfigController@deletepays');

        /**  TRAVAILLEUR  **/
        Route::post('post_travailleur', 'EmployerController@post_travailleur');
        Route::post('post_travailleur_autres', 'EmployerController@post_travailleur_autres');
        Route::post('post_edit_travailleur/{id}', 'EmployerController@post_edit_travailleur');
        Route::post('edit_travailleur/{id}', 'EmployerController@edit_travailleur');
        Route::get('/etape-deux-travailleur/{id}',['as'=>'etapedeuxtravailleur', 'uses'=>'EmployerController@etapedeuxtravailleur']);
        Route::get('/edit-travailleur/{id}',['as'=>'edittravailleur', 'uses'=>'EmployerController@edittravailleur']);
        Route::get('/details-contrat-journalier/{id}',['as'=>'lientelechargerContrat', 'uses'=>'EmployerController@lientelechargerContrat']);
        Route::get('/telecharger-contrat-journalier/{id}', ['as'=>'telechargerContratJournalier', 'uses'=>'EmployerController@telechargerContratJournalier']);
        Route::get('/telecharger-cessassion-contrat/{id}', ['as'=>'telechargerContratCessassion', 'uses'=>'EmployerController@telechargerContratCessassion']);
        Route::get('/telecharger-certificat-contrat-travail/{id}', ['as'=>'telechargerContratCertificatTravail', 'uses'=>'EmployerController@telechargerContratCertificatTravail']);
        Route::get('/telecharger-sanctions/{mat}/{idsanct}', ['as'=>'telechargerSanctions', 'uses'=>'EmployerController@telechargerSanctions']);
        Route::get('/telecharger-contrat-declaration-cnps/{id}', ['as'=>'telechargerContratDeclarationCnps', 'uses'=>'EmployerController@telechargerContratDeclarationCnps']);
        Route::get('/telecharger-fiche-precarite/{id}', ['as'=>'telechargerFichePrecarite', 'uses'=>'EmployerController@telechargerFichePrecarite']);

});
