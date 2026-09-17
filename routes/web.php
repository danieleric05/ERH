<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\RecruController;
use App\Http\Controllers\SanctionController;
use App\Http\Controllers\SanteController;
use App\Http\Controllers\VariablesController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\OffreEmploiController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CongesController;
use App\Http\Controllers\UserController;

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

// Routes publiques (avant authentification)
Route::get("/", function () {
    return view("login");
});
Route::get("/se-connecter", [HomeController::class, 'logining'])->name("login");
Route::post("post_login", [HomeController::class, 'post_login'])->middleware('throttle:5,1');

// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    // Corrected logout route
    Route::post('/logout', [HomeController::class, 'logoutUser'])->name('logout');

    // Dashboard (redirige vers /bienvenue)
    Route::get('/dashboard', function () {
        return redirect()->route('bienvenue');
    })->name('dashboard');

    // Employees (redirige vers liste des travailleurs)
    Route::get('/employees', function () {
        return redirect('/liste-travailleurs');
    })->name('employees');

    // Mon profil route
    Route::get('/mon-profil', function () {
        $id = Auth::id();
        return redirect("/monprofil/{$id}");
    })->name('mon-profil');

    // Settings (Admin seulement - Rôle 1 et 2)
    Route::get('/settings', function () {
        if (!in_array(Auth::user()->idrole, [1, 2])) {
            abort(403, 'Accès non autorisé');
        }
        return view('configuration.index'); // À créer ou rediriger
    })->name('settings');

    // Gestion des utilisateurs (Admin seulement - Rôle 1 et 2)
    Route::get('/liste-utilisateurs', [UserController::class, 'index'])->name('listeutilisateurs');
    Route::get('/ajouter-utilisateur', [UserController::class, 'create'])->name('ajouterutilisateur');
    Route::post('/post_utilisateur', [UserController::class, 'store'])->name('post_utilisateur');
    Route::get('/modifier-utilisateur/{id}', [UserController::class, 'edit'])->name('modifierutilisateur');
    Route::post('/post_edit_utilisateur/{id}', [UserController::class, 'update'])->name('post_edit_utilisateur');
    Route::get('/statut-utilisateur/{id}', [UserController::class, 'toggleStatus'])->name('statututilisateur');

    // Page de bienvenue
    Route::get("/bienvenue", function () {
        $datejour = date("Y-m-d");
        $datefincontrat = date("Y-m-d", strtotime("$datejour +20 day"));
        
        $count_fin_contrat = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("etapeid", "!=", 4)
            ->where("date_fin_contrat", "<=", $datefincontrat)
            ->where("date_fin_contrat", ">", $datejour)
            ->get();
        $count_journaler_non_declare = \App\Travailleur::where(
            "etapeid",
            "!=",
            3,
        )
            ->where("numero_securite", "!=", null)
            ->where("etapeid", "!=", 4)
            ->where("date_fin_contrat", "<=", $datefincontrat)
            ->where("date_fin_contrat", ">", $datejour)
            ->get();
        
        $termJ = "J";
        $termE = "E";
        $listeAT = \App\AccidentTravail::where("statutid", "=", 1)
            ->orderBy("id", "DESC")
            ->count();
        $data_journalier = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%",
        )
            ->where("equipeid", "!=", null)
            ->where("etapeid", "!=", 3)
            ->count();
        $data_sanctions = \App\Sanctions::where("mois", date("m"))->count();
        $data_variables = \App\Variables::where("mois", date("m"))->count();
        $data_autorisation = \App\Autorisations::where(
            "mois",
            date("m"),
        )->count();
        $data_precarite = \App\Precarites::where("statutid", 2)->get();
        $data_embauche = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termE . "%",
        )->count();
        $data_avec_arret = \App\Santes::where("mois", date("m"))
            ->where("arret_travail", "1")
            ->count();
        $data_sans_arret = \App\Santes::where("mois", date("m"))
            ->where("debut_arret", null)
            ->count();
        $data_travailleur_moisencour = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%",
        )
            ->where("mois", date("m"))
            ->where("annee", date("Y"))
            ->count();
        $article = \App\ArticleRecu::where("quantite_en_stock", "<", 50)
            ->limit(5)
            ->get();
        return view(
            "home.content",
            compact(
                "count_fin_contrat",
                "count_journaler_non_declare",
                "listeAT",
                "data_avec_arret",
                "data_sans_arret",
                "data_precarite",
                "data_autorisation",
                "data_variables",
                "data_sanctions",
                "article",
                "data_embauche",
                "data_journalier",
                "data_travailleur_moisencour",
            ),
        );
    })->name('bienvenue');

    Route::get("/monprofil/{id}", function () {
        return view("home.profil");
    });
    Route::get("/erh/recrutement", function () {
        return view("menu.recrutement.home");
    });

    /******************************* RECRUTEMENT  *******************************/
    Route::get("/inscription-ouvrier", function () {
        return view("menu.recrutement.inscription");
    });
    Route::get("/liste-complte-travailleur", function () {
        return view("menu.recrutement.listecomplete");
    });

    /******************************* TRAVAILLEUR  *******************************/
    Route::get("/liste-embauches", function (\Illuminate\Http\Request $request) {
        $termJ = "E";
        $data_unites = \App\Unites::get();
        $data_equipes = \App\Equipes::get();
        $data_departements = \App\Departement::get();
        $data_pays = \App\Pays::get();
        
        $query = \App\Travailleur::where(
            "matricule",
            "like",
            $termJ . "%",
        );

        if (!empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $sortable = ['matricule' => 'matricule', 'nom' => 'nom', 'embauche' => 'date_debut_contrat', 'fin' => 'date_fin_contrat'];
        $sort = $sortable[$request->input('sort')] ?? 'id';
        $dir = $request->input('dir') === 'asc' ? 'asc' : 'desc';
        $perPage = in_array((int) $request->input('per_page'), [25, 50, 100, 200]) ? (int) $request->input('per_page') : 50;

        $data_journalier = $query->orderBy($sort, $dir)->paginate($perPage)->appends($request->query());

        return view(
            "travailleur.liste_embauches",
            compact(
                "data_journalier",
                "data_pays",
                "data_unites",
                "data_equipes",
                "data_departements",
            ),
        );
    })->name("liste_embauches");
    Route::get("/ajouter-autres-travailleur", function () {
        $termJ = "J";
        $data_unites = \App\Unites::get();
        $data_equipes = \App\Equipes::get();
        $data_fonctions = \App\Fonction::get();
        $data_departements = \App\Departement::get();
        $data_pays = \App\Pays::get();

        $data_journalier = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%"
        )
            ->limit(10)
            ->get();
        
        $chiffre = $data_journalier->first()->matricule ?? '';
        $chiffre = substr($chiffre, 4);

        return view(
            "travailleur.addautres",
            compact(
                "data_journalier",
                "chiffre", 
                "data_fonctions",
                "data_pays",
                "data_unites",
                "data_equipes",
                "data_departements"
            )
        );
    });
    Route::get("/ajouter-travailleur-etape-un", function () {
        $termJ = "J";
        $data_unites = \App\Unites::get();
        $data_typecontrat = \App\TypeContrat::get();
        $data_equipes = \App\Equipes::get();
        $data_departements = \App\Departement::get();
        $data_pays = \App\Pays::get();
        $data_journalier = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%",
        )
            ->latest("id")
            ->first();

        $mat_journalier = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%",
        )
            ->orderBy("id", "DESC")
            ->limit(10)
            ->get();

        $maj_big = ''; 
        if ($data_journalier) { 
            foreach ($mat_journalier as $jour) {
                if ($data_journalier->matricule < $jour->matricule) {
                    $maj_big = substr($jour->matricule, 4);
                } else {
                    $maj_big = substr($data_journalier->matricule, 4);
                }
            }
        }

        return view(
            "travailleur.add",
            compact(
                "data_journalier",
                "maj_big",
                "data_typecontrat",
                "data_pays",
                "data_unites",
                "data_equipes",
                "data_departements",
            ),
        );
    });
    
    Route::get("/etape-deux-travailleur/{id}", [EmployerController::class, 'etapedeuxtravailleur'])->name("etapedeuxtravailleur");
    Route::get("/telecharger-contrat/{slug}", function () {
        return view("travailleur.contrat");
    });

    Route::get(
        "action/declaration/travailleurs",
        [RecruController::class, 'declaration'],
    );
    Route::post("post_search", [RecruController::class, 'post_search']);
    Route::post(
        "post_search_varaiables",
        [RecruController::class, 'post_search_varaiables'],
    );
    Route::get("reconduire/journalier", [RecruController::class, 'reconduireJournalier']);
    Route::get("/action-telecharger-contrat/{id}", [RecruController::class, 'actionsContrat'])->name("actionsContrat");
    Route::get("/telecharger-exel-travailleurs/{code}", [RecruController::class, 'excel_download'])->name("excel_download");
    Route::get("/telecharger-quinzaine-/{code}", [RecruController::class, 'excel_download_quinzaine'])->name("excel_download_quinzaine");
    Route::get("/telecharger-exel-travailleurs-fin-contrat", [RecruController::class, 'excel_download_fin_contrat'])->name("excel_download_fin_contrat");
    Route::get("/telecharger-exel-variables-travailleurs/{code}", [RecruController::class, 'excel_download_variables'])->name("excel_download_variables");
    Route::get("/telecharger-exel-odoo/{code}", [RecruController::class, 'excel_download_odoo'])->name("excel_download_odoo");
    Route::get("/travailleurs-contrat-cessations", [RecruController::class, 'liste_cessations'])->name("liste_cessations");
    Route::get("/travailleurs-contrat-declarations-cnps", [RecruController::class, 'liste_declarations'])->name("liste_declarations");
    Route::post(
        "update/declarations/updatedeclaration",
        [RecruController::class, 'updatedeclaration'],
    );
    Route::post(

        "update/reconduire/uptravailleur",
        [RecruController::class, 'updatereconduire'],
    );
    Route::get("/liste-travailleurs-etapes-deux", [RecruController::class, 'listetravailleurs'])->name("listetravailleurs");
    Route::get("/liste-tous-les-travailleurs", [RecruController::class, 'liste_tous_travailleurs'])->name("liste_tous_travailleurs");
    Route::get("/tous-les-travailleurs", [RecruController::class, 'liste_travailleurs'])->name("liste_travailleurs");
    Route::get("/liste-certificat-de-travails", [RecruController::class, 'liste_certificat_travail'])->name("liste_certificat_travail");
    Route::get("/historiques-travailleurs", [RecruController::class, 'historique'])->name("historiques");
    Route::get("/patient-recu/{id}", [RecruController::class, 'patientrexu'])->name("patientrexu");
    Route::get("/variables-santes/{id}", [SanctionController::class, 'variables_sante'])->name("variables_sante");
    Route::get("/historiques-contrat/{id}", [RecruController::class, 'historiques_contrat'])->name("historiques_contrat");

    /** MISSIONS */
    Route::get("/journaliers-fin-contrat", function () {
        $datejour = date("Y-m-d");
        $termE = "J";
        $datefincontrat = date("Y-m-d", strtotime("$datejour +20 day"));
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("etapeid", "!=", 4)
            ->where("matricule", "like", "%" . $termE . "%")
            ->where("date_fin_contrat", "<=", $datefincontrat)
            ->where("date_fin_contrat", ">", $datejour)
            ->get();
        return view(
            "travailleur.listejournalierfin_contrat",
            compact("data_travailleur"),
        );
    })->name("journaliers_fin_contrat");
    Route::get("/ajouter-autorisation", function () {
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->orderBy("id", "DESC")
            ->get();
        return view("autorisations.add", compact("data_travailleur"));
    });
    Route::get("/liste-autorisations", [EmployerController::class, 'listeautorisations'])->name("listeautorisations");
    Route::get("/liste-missions", [EmployerController::class, 'listemissions'])->name("listemissions");
    Route::get("/calenajouter-ha01drier-calendrier", [EmployerController::class, 'calendrier_conges'])->name("calendrier_conges");

    /** CONGES */
    Route::get("/ajouter-conges", [CongesController::class, 'ajouter'])->name("ajouterConges");
    Route::post("post_conges", [CongesController::class, 'post_conges']);
    Route::get("/liste-conges", [CongesController::class, 'liste'])->name("listeconges");
    Route::get("/valider-conge/{id}", [CongesController::class, 'valider'])->name("validerConge");
    Route::get("/refuser-conge/{id}", [CongesController::class, 'refuser'])->name("refuserConge");

    /** ========================================
     *  ARCHIVÉ - PRECARITE
     *  Plus d'actualité - Pour réactiver : décommenter cette section
     *  ======================================== */
    /* Route::get("/ajouter-ha01", function () {
        $code = "";
        $data_hao1 = \App\HAO1::orderBy("id", "DESC")->get();
        $verif_traitement_ha01 = \App\HAO1::where("statutid", 2)->count();
        return view(
            "precarite.add",
            compact("data_hao1", "verif_traitement_ha01", "code"),
        );
    });
    Route::get("/historique-ha01", [EmployerController::class, 'historique_ha01'])->name("historique_ha01");
    Route::get("/finaliser-ha01", [EmployerController::class, 'finaliser_ha01'])->name("finaliser_ha01");
    Route::get("/exporter-precarites-exels/{code}", [EmployerController::class, 'exels_precarites'])->name("exels_precarites");
    Route::get("/exporter-precarites-avant-finaliser", [EmployerController::class, 'exels_precarites_debut'])->name("exels_precarites_debut");
    Route::post("post_precarite_ho", [EmployerController::class, 'post_precarite_ho']);
    Route::post(
        "post_precarite_finalite",
        [EmployerController::class, 'post_precarite_finalite'],
    );
    Route::post(
        "post_search_histo_precarite",
        [EmployerController::class, 'post_search_histo_precarite'],
    ); */

    /** VARIABLES */
    Route::get("/variables", function () {
        return view("variables.variables", compact("data_travailleur"));
    });
    Route::get("/ajouter-variable", function () {
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->orderBy("id", "DESC")
            ->get();
        return view("variables.add", compact("data_travailleur"));
    });
    Route::get("/ajouter-heure-supplementaire", function () {
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->orderBy("id", "DESC")
            ->get();
        $variableHS = \App\Variables::Where("cause", 2)
            ->orderBy("id", "DESC")
            ->get();
        return view(
            "variables.heure_sup",
            compact("data_travailleur", "variableHS"),
        );
    });
    Route::get("/ajouter-autres-variables", function () {
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->orderBy("id", "DESC")
            ->get();
        $variableHS = \App\Variables::Where("cause", 2)
            ->orderBy("id", "DESC")
            ->get();
        return view(
            "variables.autres_variables",
            compact("data_travailleur", "variableHS"),
        );
    });
    Route::post(
        "post_variables_manuelle",
        [VariablesController::class, 'post_variables_manuelle'],
    );
    Route::post(
        "post_autres_variables",
        [VariablesController::class, 'post_autres_variables'],
    );
    Route::post(
        "post_variables_heure_sup",
        [VariablesController::class, 'post_variables_heure_sup'],
    );
    // ARCHIVÉ - Précarité : Route::get("/precarite-calcules", [EmployerController::class, 'precarite_calc'])->name("precarite_calc");
    Route::get("/liste-variables-automatique", [VariablesController::class, 'listevariables_automatique'])->name("listevariables_automatique");
    Route::get("/liste-variables-manuelles", [VariablesController::class, 'listevariables_manuelle'])->name("listevariables_manuelle");
    Route::get("/liste-variables-heure-supplementaire", [VariablesController::class, 'listevariables_heure_supp'])->name("listevariables_heure_supp");
    Route::get("/liste-variables-autres-variables", [VariablesController::class, 'listevariables_autres_variables'])->name("listevariables_autres_variables");
    Route::get("/historiques-variables", [EmployerController::class, 'historiques_variables'])->name("historiques_variables");
    // ARCHIVÉ - Précarité : Route::get("/liste-precarites", [EmployerController::class, 'listeprecarites'])->name("listeprecarites");

    /** SANCTION */
    Route::get("/ajouter-sanction", function () {
        $termE = "E";
        $data_travailleur = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termE . "%",
        )
            ->where("equipeid", "!=", null)
            ->where("etapeid", "!=", 3)
            ->orderBy("id", "DESC")
            ->get();
        $data_travailleurJour = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->where("equipeid", "!=", null)
            ->orderBy("id", "DESC")
            ->get();
        return view(
            "sanctions.add",
            compact("data_travailleur", "data_travailleurJour"),
        );
    });
    Route::get("/sanctions-autres-travailleur", function () {
        $data_travailleur = \App\Travailleur::where("statutid", 4)
            ->orderBy("id", "DESC")
            ->get();
        $data_travailleurJour = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->orderBy("id", "DESC")
            ->get();
        return view(
            "sanctions.addautres_sanction",
            compact("data_travailleur", "data_travailleurJour"),
        );
    });
    Route::get("/modifier-sanction", function () {
        $data_travailleur = \App\Travailleur::where("etapeid", "!=", 3)
            ->where("statutid", "!=", 4)
            ->orderBy("id", "DESC")
            ->get();
        return view("sanctions.edit", compact("data_travailleur"));
    });
    Route::get("/liste-sanction", [SanctionController::class, 'listes_sanctions'])->name("listesanctions");
    Route::get("/telecharger-sanction/{id}", [SanctionController::class, 'techarger_sanctions'])->name("techarger_sanctions");
    Route::get("/sanction-variable/{id}", [SanctionController::class, 'sanctionvariable'])->name("sanctionvariable");
    Route::get("/add-autorisation-variables/{id}", [SanctionController::class, 'autorisationvariable'])->name("autorisationvariable");
    Route::get("/add-mission-variables/{id}", [SanctionController::class, 'missionvariable'])->name("missionvariable");
    Route::post("post_sanction", [SanctionController::class, 'post_sanction']);
    Route::post("post_autorisation", [SanctionController::class, 'post_autorisation']);

    /** SANTE */
    Route::get("santes", [SanteController::class, 'index_santes'])->name("santes");
    Route::get(
        "ajouter-accident-travail",
        [SanteController::class, 'accident_travail'],
    )->name("accidentTravail");
    Route::post("post_sante", [SanteController::class, 'post_sante']);
    Route::post(
        "post_accident_travail",
        [SanteController::class, 'post_accident_travail'],
    );
    Route::post(
        "post_historiques_sante",
        [SanteController::class, 'post_historiques_sante'],
    );
    Route::get("/ajouter-consultation", [SanteController::class, 'index_santes']);
    Route::get("/liste-accident-travail", [SanteController::class, 'listesaccident'])->name("listesaccident");
    Route::get("/liste-consultations", [SanteController::class, 'listesconsultation'])->name("listesconsultation");
    Route::get("/historique-consultations", [SanteController::class, 'historique_consultation'])->name("historique_consultation");
    Route::get("/accident-travail-traiter/{id}", [SanteController::class, 'accident_travail_traiter'])->name("accident_travail_traiter");

    Route::get("/detecter-matricule", [EmployerController::class, 'detect_matricul'])->name("detect_matricul");

    /** ========================================
     *  ARCHIVÉ - TENUE
     *  Plus d'actualité - Pour réactiver : décommenter cette section
     *  ======================================== */
    /* Route::get("/ajouter-tenue", function () {
        $termJ = "J";
        $data_travailleur = \App\Travailleur::where(
            "matricule",
            "like",
            "%" . $termJ . "%",
        )
            ->where("etapeid", "!=", 3)
            ->orderBy("id", "DESC")
            ->get();
        $data_ArticleRecu = \App\ArticleRecu::orderBy("id", "DESC")->get();
        $data_Services = \App\Services_tenue::orderBy("id", "DESC")->get();
        return view(
            "tenues.add",
            compact("data_travailleur", "data_Services", "data_ArticleRecu"),
        );
    });
    Route::get("chager_etat/{id}", [EmployerController::class, 'chager_etat']);
    Route::post("post_gestion_tenue", [EmployerController::class, 'post_gestion_tenue']);
    Route::post("post_appro_stock", [EmployerController::class, 'post_appro_stock']);
    Route::get("/liste-tenues", [EmployerController::class, 'listetenues'])->name("listetenues");
    Route::get("/stock-tenues", [EmployerController::class, 'stock_tenues'])->name("stock_tenues");
    Route::get("/approvisionner-stock-tenues", [EmployerController::class, 'appro_stock'])->name("appro_stock"); */

    Route::get("departements", [ConfigController::class, 'index'])->name("departements");
    Route::post(
        "add/departements/adddepartements",
        [ConfigController::class, 'adddepartements'],
    );
    Route::post(
        "update/departements/updatedepartements",
        [ConfigController::class, 'updatedepartements'],
    );
    Route::get(
        "edit/departements/data",
        [ConfigController::class, 'editdepartementsurl'],
    );
    Route::get(
        "delete/departements/data",
        [ConfigController::class, 'deletedepartements'],
    );
    /******************************* EQUIPE *******************************************/
    Route::get("equipes", [ConfigController::class, 'index_equipes'])->name("equipes");
    Route::post("add/equipes/addequipes", [ConfigController::class, 'addequipes']);
    Route::post(
        "update/equipes/updateequipes",
        [ConfigController::class, 'updateequipes'],
    );
    Route::get("edit/equipes/data", [ConfigController::class, 'editequipesurl']);
    Route::get("delete/equipes/data", [ConfigController::class, 'deleteequipes']);

    /******************************* UNITES *******************************************/

    Route::get("unites", [ConfigController::class, 'index_unites'])->name("unites");
    Route::post("add/unites", [ConfigController::class, 'addunites']);
    Route::post("update/unites/updateunites", [ConfigController::class, 'updateunites']);
    Route::get("edit/unites/data", [ConfigController::class, 'editunitessurl']);
    Route::get("delete/unites/data", [ConfigController::class, 'deleteunites']);

    /******************************* FONCTION *******************************************/

    Route::get("fonctions", [ConfigController::class, 'index_fonction'])->name(
        "fonctions",
    );
    Route::post("add/fonctions/fonctions", [ConfigController::class, 'addfonctions']);
    Route::post(
        "update/fonctions/updatefonctions",
        [ConfigController::class, 'updatefonctions'],
    );
    Route::get("edit/fonctions/data", [ConfigController::class, 'editfonctionsurl']);
    Route::get("delete/fonctions/data", [ConfigController::class, 'deletefonctions']);

    /******************************* NIVEAU ETUDE *******************************************/
    Route::get("categories", [ConfigController::class, 'index_categories'])->name(
        "categories",
    );

    Route::post(
        "add/categories/addcategories",
        [ConfigController::class, 'addcategories'],
    );
    Route::post(
        "update/categories/updatecategories",
        [ConfigController::class, 'updatecategories'],
    );
    Route::get("edit/categories/data", [ConfigController::class, 'editcategoriesurl']);
    Route::get("delete/categories/data", [ConfigController::class, 'deletecategories']);

    Route::get("niveauEtude", [ConfigController::class, 'index_niveauEtude'])->name(
        "niveauEtude",
    );

    Route::post(
        "add/niveauEtude/addniveauEtude",
        [ConfigController::class, 'addniveauEtude'],
    );
    Route::post(
        "update/niveauEtude/updateniveauEtude",
        [ConfigController::class, 'updateniveauEtude'],
    );
    Route::get("edit/niveauEtude/data", [ConfigController::class, 'editniveauEtudeurl']);
    Route::get("delete/niveauEtude/data", [ConfigController::class, 'deleteniveauEtude']);

    /******************************* PAYS *******************************************/

    Route::get("pays", [ConfigController::class, 'index_pays'])->name("pays");
    Route::post("add/pays/addpays", [ConfigController::class, 'addpays']);
    Route::post("update/pays/updatepays", [ConfigController::class, 'updatepays']);
    Route::get("edit/pays/data", [ConfigController::class, 'editpaysurl']);
    Route::get("delete/pays/data", [ConfigController::class, 'deletepays']);

    /** TRAVAILLEUR **/
    Route::post("post_travailleur", [EmployerController::class, 'post_travailleur']);
    Route::post(
        "post_travailleur_autres",
        [EmployerController::class, 'post_travailleur_autres'],
    );
    Route::post(
        "post_edit_travailleur/{id}",
        [EmployerController::class, 'post_edit_travailleur'],
    );
    Route::post(
        "delete_photo_travailleur/{id}",
        [EmployerController::class, 'delete_photo_travailleur']
    )->name('delete_photo_travailleur');
    Route::post("edit_travailleur/{id}", [EmployerController::class, 'edit_travailleur']);
    Route::get("/details-contrat-journalier/{id}", [EmployerController::class, 'lientelechargerContrat'])->name("lientelechargerContrat");
    Route::get("/telecharger-contrat-journalier/{id}", [EmployerController::class, 'telechargerContratJournalier'])->name("telechargerContratJournalier");
    Route::get("/telecharger-contrat-cdd/{id}", [EmployerController::class, 'telechargerContratCDD'])->name("telechargerContratCDD");
    Route::get("/telecharger-contrat-cdi/{id}", [EmployerController::class, 'telechargerContratCDI'])->name("telechargerContratCDI");
    Route::get("/telecharger-cessassion-contrat/{id}", [EmployerController::class, 'telechargerContratCessassion'])->name("telechargerContratCessassion");
    Route::get("/telecharger-certificat-contrat-travail/{id}", [EmployerController::class, 'telechargerContratCertificatTravail'])->name("telechargerContratCertificatTravail");
    Route::get("/telecharger-sanctions/{mat}/{idsanct}", [EmployerController::class, 'telechargerSanctions'])->name("telechargerSanctions");
    Route::get("/telecharger-contrat-declaration-cnps/{id}", [EmployerController::class, 'telechargerContratDeclarationCnps'])->name("telechargerContratDeclarationCnps");
    // ARCHIVÉ - Précarité : Route::get("/telecharger-fiche-precarite/{id}", [EmployerController::class, 'telechargerFichePrecarite'])->name("telechargerFichePrecarite");

    Route::get('/api/calendrier/evenements', [EmployerController::class, 'getEvenementsCalendrier'])->name('calendrier.evenements');

    /******************************* RECRUTEMENT - OFFRES D'EMPLOI *******************************************/

    Route::get('/recrutement/offres', [OffreEmploiController::class, 'index'])->name('offres.index');
    Route::get('/recrutement/offres/create', [OffreEmploiController::class, 'create'])->name('offres.create');
    Route::post('/recrutement/offres', [OffreEmploiController::class, 'store'])->name('offres.store');
    Route::get('/recrutement/offres/{id}', [OffreEmploiController::class, 'show'])->name('offres.show');
    Route::get('/recrutement/offres/{id}/edit', [OffreEmploiController::class, 'edit'])->name('offres.edit');
    Route::put('/recrutement/offres/{id}', [OffreEmploiController::class, 'update'])->name('offres.update');
    Route::post('/recrutement/offres/{id}/close', [OffreEmploiController::class, 'close'])->name('offres.close');

    /******************************* RECRUTEMENT - CANDIDATURES *******************************************/

    Route::get('/recrutement/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/recrutement/candidatures/kanban', [CandidatureController::class, 'kanban'])->name('candidatures.kanban');
    Route::get('/recrutement/candidatures/{id}', [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::post('/recrutement/candidatures/{id}/statut', [CandidatureController::class, 'changerStatut'])->name('candidatures.statut');
    Route::post('/recrutement/candidatures/{id}/assigner', [CandidatureController::class, 'assignerRecruteur'])->name('candidatures.assigner');
    Route::post('/recrutement/candidatures/{id}/rejeter', [CandidatureController::class, 'rejeter'])->name('candidatures.rejeter');
    Route::post('/recrutement/candidatures/{id}/embaucher', [CandidatureController::class, 'convertirEnTravailleur'])->name('candidatures.embaucher');
    Route::get('/recrutement/candidatures/{id}/evaluer', [CandidatureController::class, 'evaluer'])->name('candidatures.evaluer');
    Route::post('/recrutement/candidatures/{id}/evaluation', [CandidatureController::class, 'storeEvaluation'])->name('candidatures.evaluation');
});