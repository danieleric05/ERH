<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            <img src="{{ asset('rhassets/images/images.png') }}" height="50" width="50" class="rounded-circle user-photo">
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{ Auth::user()?->name }}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                    <li><a href="{{ url('monprofil', Auth::user()?->id) }}"><i class="icon-user"></i>Mon Profil</a></li>
                    <li class="divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 px-4 block text-sm text-gray-700 hover:bg-slate-100">
                                <i class="icon-power"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center">
                    <h6>@if(Auth::user()?->idrole == 1) ADMIN @endif @if(Auth::user()?->idrole == 2) ASSISTANTE @endif</h6>
                </div>
            </div>
        </div>

        <!-- Nav tabs -->

        @if((Auth::user()?->idrole == 4) || (Auth::user()?->idrole == 5))
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#hr_menu">ERH</a></li>
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#project_menu">Modules</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sub_menu"><i class="icon-grid"></i></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>
            </ul>

            <div class="tab-content p-l-0 p-r-0">

                <div class="tab-pane animated fadeIn" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>ERH Dashboard</span></a></li>
                        </ul>
                    </nav>
                </div>

                <div class="tab-pane animated fadeIn active" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="active"><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>Espace santé</span></a></li>
                            <li>
                                <a href="#Sante" class="has-arrow"><i class="icon-users"></i><span>Consultation</span></a>
                                <ul>
                                    <li><a href="{{ route('santes') }}">Ajouter</a></li>
                                    <li><a href="{{ route('listesconsultation') }}">Voir la liste</a></li>
                                    <li><a href="{{ route('historique_consultation') }}">Historiques consultations</a></li>
                                </ul>
                            </li>
							<li>
                                <a href="#Sante" class="has-arrow"><i class="icon-users"></i><span>Accident de travail</span></a>
                                <ul>
                                    <li><a href="{{ route('accidentTravail') }}">Ajouter</a></li>
                                    <li><a href="{{ route('listesaccident') }}">Voir la liste</a></li>
                                    <li><a href="{{ route('historique_consultation') }}">Historiques Accident de travail</a></li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn" id="sub_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">

                            <li>
                                <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Mon profil</span></a>
                                <ul>
                                    <li><a href="#">Information personnel</a></li>
                                    <li><a href="#">Paramètre de connexion</a></li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn" id="setting">
                    <div class="p-l-15 p-r-15">
                        <h6>Choose Skin</h6>
                        <ul class="choose-skin list-unstyled">
                            <li data-theme="purple">
                                <div class="purple"></div>
                                <span>Purple</span>
                            </li>
                            <li data-theme="blue">
                                <div class="blue"></div>
                                <span>Blue</span>
                            </li>
                            <li data-theme="cyan">
                                <div class="cyan"></div>
                                <span>Cyan</span>
                            </li>
                            <li data-theme="green">
                                <div class="green"></div>
                                <span>Green</span>
                            </li>
                            <li data-theme="orange" class="active">
                                <div class="orange"></div>
                                <span>Orange</span>
                            </li>
                            <li data-theme="blush">
                                <div class="blush"></div>
                                <span>Blush</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()?->idrole == 6)
			
			<ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#hr_menu">ERH</a></li>
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#project_menu">Modules</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sub_menu"><i class="icon-grid"></i></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>
            </ul>
			
			<div class="tab-content p-l-0 p-r-0">
                <div class="tab-pane animated fadeIn" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>ERH Dashboard</span></a></li>

                            <li>
                                <a href="#Employees" class="has-arrow"><i class="icon-users"></i><span>Offres d'emploi</span></a>
                                <ul>
                                    <li><a href="#">Ajouter une offre</a></li>
                                    <li><a href="#">Liste des offres</a></li>
                                </ul>
                            </li>

                            <li><a href="#"><i class="icon-calendar"></i>CV-Thèque</a></li>

                            <li><a href="#"><i class="icon-badge"></i>Activities</a></li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn active" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="active"><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>

                            <li>
                                <a href="#Recrutement" class="has-arrow"><i class="icon-users"></i><span>Recrutement</span></a>
                                <ul>
                                    <!--<li><a href="{{ url('ajouter-travailleur-etape-un') }}">Recrutement 1ère Étape</a></li>
                                    <li><a href="{{ route('listetravailleurs') }}">Recrutement 2ème Étape</a></li>-->
                                    <li><a href="{{ route('liste_tous_travailleurs') }}">Liste des travailleurs</a></li>
                                    <!--<li><a href="{{ route('liste_cessations') }}">Liste des cessations</a></li>
                                    <li><a href="{{ route('liste_certificat_travail') }}">Liste des certificats de travail</a></li>
                                    <li><a href="{{ route('liste_declarations') }}">Liste des declarations cnps</a></li>-->
                                    <li><a href="{{ route('historiques') }}">Rechercher</a></li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="#Tenues" class="has-arrow"><i class="icon-users"></i><span>Gestion des tenues</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-tenue') }}">Attribuer une tenue</a></li>
                                    <li><a href="{{ route('listetenues') }}">Liste des tenues attribuées</a></li>
                                    <li><a href="{{ route('appro_stock') }}">Approvisionner le stock</a></li>
                                    <li><a href="{{ route('stock_tenues') }}">Stock des tenues</a></li>
                                </ul>
                            </li>
                            <!--<li>
                                <a href="#Sante" class="has-arrow"><i class="icon-users"></i><span>Espace santé</span></a>
                                <ul>
                                    <li><a href="{{ route('listesaccident') }}">Liste accident de travail</a></li>
                                    <li><a href="{{ route('listesconsultation') }}">Liste des consultations</a></li>
                                </ul>
                            </li>-->

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn" id="sub_menu">

                        <nav class="sidebar-nav">
                            <ul class="main-menu metismenu">

                                <li>
                                    <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Mon profil</span></a>
                                    <ul>
                                        <li><a href="#">Information personnel</a></li>
                                        <li><a href="#">Paramètre de connexion</a></li>
                                    </ul>
                                </li>
                                @if((Auth::user()?->idrole == 2) || (Auth::user()?->idrole == 1))
                                <li>
                                    <a href="#FileManager" class="has-arrow"><i class="icon-folder"></i> <span>Setting</span></a>
                                    <ul>
                                        <li><a href="{{ route('unites') }}">Unité</a></li>
                                        <li><a href="{{ route('departements') }}">Département</a></li>
                                        <li><a href="{{ route('equipes') }}">Équipe</a></li>
                                        <li><a href="{{ route('niveauEtude') }}">Niveau d'étude</a></li>
                                        <li><a href="{{ route('categories') }}">Catégorie</a></li>
                                        <li><a href="{{ route('fonctions') }}">Fonction</a></li>
                                        <li><a href="{{ route('pays') }}">Pays</a></li>
                                    </ul>
                                </li>
                                @endif
                            </ul>
                        </nav>

                </div>
               
            </div>
			
		
		@endif
	
        @if((Auth::user()?->idrole == 2))

            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#hr_menu">ERH</a></li>
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#project_menu">Modules</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sub_menu"><i class="icon-grid"></i></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
                <div class="tab-pane animated fadeIn" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>ERH Dashboard</span></a></li>

                            <li>
                                <a href="#Employees" class="has-arrow"><i class="icon-users"></i><span>Offres d'emploi</span></a>
                                <ul>
                                    <li><a href="#">Ajouter une offre</a></li>
                                    <li><a href="#">Liste des offres</a></li>
                                </ul>
                            </li>

                            <li><a href="#"><i class="icon-calendar"></i>CV-Thèque</a></li>

                            <li><a href="#"><i class="icon-badge"></i>Activities</a></li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn active" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="active"><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>

                            <li>
                                <a href="#Recrutement" class="has-arrow"><i class="icon-users"></i><span>Recrutement</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-travailleur-etape-un') }}">Recrutement 1ère Étape</a></li>
                                    <li><a href="{{ route('listetravailleurs') }}">Recrutement 2ème Étape</a></li>
                                    <li><a href="{{ route('liste_tous_travailleurs') }}">Liste des travailleurs</a></li>
                                    <li><a href="{{ route('liste_cessations') }}">Liste des cessations</a></li>
                                    <li><a href="{{ route('liste_certificat_travail') }}">Liste des certificats de travail</a></li>
                                    <li><a href="{{ route('liste_declarations') }}">Liste des declarations cnps</a></li>
                                    <li><a href="{{ route('historiques') }}">Rechercher</a></li>
                                </ul>
                            </li>


                            <li>
                                <a href="#Precarites" class="has-arrow"><i class="icon-users"></i><span>Gestion des Precarités</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-ha01') }}">Importer HAO1</a></li>
                                    <li><a href="{{ route('historique_ha01') }}">Historique HA01</a></li>
                                    <li><a href="{{ route('listeprecarites') }}">Liste des précarités</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Variables" class="has-arrow"><i class="icon-users"></i><span>Gestion des Variables</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-variable') }}">Ajouter une variable(manuelle)</a></li>
                                    <li><a href="{{ url('ajouter-heure-supplementaire') }}">Ajouter une heure supplémentaire</a></li>
                                    <li><a href="{{ url('ajouter-autres-variables') }}">Ajouter autres variables</a></li>
                                    <li><a href="{{ route('listevariables_manuelle') }}">Liste des variables(manuelles)</a></li>
                                    <li><a href="{{ route('listevariables_heure_supp') }}">Liste des heures-supplementaire</a></li>
                                    <li><a href="{{ route('listevariables_automatique') }}">Liste des variables(automatique)</a></li>
                                    <li><a href="{{ route('historiques_variables') }}">Historiques des variables</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Missions" class="has-arrow"><i class="icon-users"></i><span>Gestion des autorisations</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-autorisation') }}">Ajouter une autorisation</a></li>
                                    <li><a href="{{ route('listemissions') }}">Liste des missions</a></li>
                                    <li><a href="{{ route('listeautorisations') }}">Liste des autorisations</a></li>
                                    <li><a href="{{ route('ajouterConges') }}">Ajouter un congé</a></li>
                                    <li><a href="{{ route('listeconges') }}">Liste des congés</a></li>
                                    <li><a href="{{ route('calendrier_conges') }}">Calendrier des congés</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Sanction" class="has-arrow"><i class="icon-users"></i><span>Gestion des sanctions</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-sanction') }}">Ajouter une sanction</a></li>
                                    <li><a href="{{ route('listesanctions') }}">Liste des sanctions</a></li>
                                </ul>
                            </li>
                            <!--<li>
                                <a href="#Tenues" class="has-arrow"><i class="icon-users"></i><span>Gestion des tenues</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-tenue') }}">Attribuer une tenue</a></li>
                                    <li><a href="{{ route('listetenues') }}">Liste des tenues attribuées</a></li>
                                    <li><a href="{{ route('appro_stock') }}">Approvisionner le stock</a></li>
                                    <li><a href="{{ route('stock_tenues') }}">Stock des tenues</a></li>
                                </ul>
                            </li>-->
                            <li>
                                <a href="#Sante" class="has-arrow"><i class="icon-users"></i><span>Espace santé</span></a>
                                <ul>
                                    <li><a href="{{ route('listesaccident') }}">Liste accident de travail</a></li>
                                    <li><a href="{{ route('listesconsultation') }}">Liste des consultations</a></li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn" id="sub_menu">

                        <nav class="sidebar-nav">
                            <ul class="main-menu metismenu">

                                <li>
                                    <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Mon profil</span></a>
                                    <ul>
                                        <li><a href="#">Information personnel</a></li>
                                        <li><a href="#">Paramètre de connexion</a></li>
                                    </ul>
                                </li>
                                @if((Auth::user()?->idrole == 2) || (Auth::user()?->idrole == 1))
                                <li>
                                    <a href="#FileManager" class="has-arrow"><i class="icon-folder"></i> <span>Setting</span></a>
                                    <ul>
                                        <li><a href="{{ route('unites') }}">Unité</a></li>
                                        <li><a href="{{ route('departements') }}">Département</a></li>
                                        <li><a href="{{ route('equipes') }}">Équipe</a></li>
                                        <li><a href="{{ route('niveauEtude') }}">Niveau d'étude</a></li>
                                        <li><a href="{{ route('categories') }}">Catégorie</a></li>
                                        <li><a href="{{ route('fonctions') }}">Fonction</a></li>
                                        <li><a href="{{ route('pays') }}">Pays</a></li>
                                    </ul>
                                </li>
                                @endif
                            </ul>
                        </nav>

                </div>
                <div class="tab-pane animated fadeIn" id="setting">
                    <div class="p-l-15 p-r-15">
                        <h6>Choose Skin</h6>
                        <ul class="choose-skin list-unstyled">
                            <li data-theme="purple">
                                <div class="purple"></div>
                                <span>Purple</span>
                            </li>
                            <li data-theme="blue">
                                <div class="blue"></div>
                                <span>Blue</span>
                            </li>
                            <li data-theme="cyan">
                                <div class="cyan"></div>
                                <span>Cyan</span>
                            </li>
                            <li data-theme="green">
                                <div class="green"></div>
                                <span>Green</span>
                            </li>
                            <li data-theme="orange" class="active">
                                <div class="orange"></div>
                                <span>Orange</span>
                            </li>
                            <li data-theme="blush">
                                <div class="blush"></div>
                                <span>Blush</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        @endif

        @if((Auth::user()?->idrole == 1))
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#hr_menu">ERH</a></li>
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#project_menu">Modules</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sub_menu"><i class="icon-grid"></i></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content p-l-0 p-r-0">
                <div class="tab-pane animated fadeIn" id="hr_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>ERH Dashboard</span></a></li>

                            <li>
                                <a href="#Employees" class="has-arrow"><i class="icon-users"></i><span>Offres d'emploi</span></a>
                                <ul>
                                    <li><a href="#">Ajouter une offre</a></li>
                                    <li><a href="#">Liste des offres</a></li>
                                </ul>
                            </li>

                            <li><a href="#"><i class="icon-calendar"></i>CV-Thèque</a></li>

                            <li><a href="#"><i class="icon-badge"></i>Activities</a></li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn active" id="project_menu">
                    <nav class="sidebar-nav">
                        <ul class="main-menu metismenu">
                            <li class="active"><a href="{{ url('bienvenue') }}"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>

                            <li>
                                <a href="#Recrutement" class="has-arrow"><i class="icon-users"></i><span>Recrutement</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-travailleur-etape-un') }}">Recrutement 1ère Étape</a></li>
                                    <li><a href="{{ route('listetravailleurs') }}">Recrutement 2ème Étape</a></li>
                                    <li><a href="{{ route('liste_tous_travailleurs') }}">Liste des travailleurs</a></li>
                                    <li><a href="{{ route('liste_cessations') }}">Liste des cessations</a></li>
                                    <li><a href="{{ route('liste_certificat_travail') }}">Liste des certificats de travail</a></li>
                                    <li><a href="{{ route('liste_declarations') }}">Liste des declarations cnps</a></li>
                                    <li><a href="{{ route('historiques') }}">Rechercher</a></li>
                                </ul>
                            </li>


                            <li>
                                <a href="#Precarites" class="has-arrow"><i class="icon-users"></i><span>Gestion des Precarités</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-ha01') }}">Importer HAO1</a></li>
                                    <li><a href="{{ route('historique_ha01') }}">Historique HA01</a></li>
                                    <li><a href="{{ route('listeprecarites') }}">Liste des précarités</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Variables" class="has-arrow"><i class="icon-users"></i><span>Gestion des Variables</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-variable') }}">Ajouter une variable(manuelle)</a></li>
                                    <li><a href="{{ url('ajouter-heure-supplementaire') }}">Ajouter une heure supplémentaire</a></li>
                                    <li><a href="{{ url('ajouter-autres-variables') }}">Ajouter autres variables</a></li>
                                    <li><a href="{{ route('listevariables_manuelle') }}">Liste des variables(manuelles)</a></li>
                                    <li><a href="{{ route('listevariables_heure_supp') }}">Liste des heures-supplementaire</a></li>
                                    <li><a href="{{ route('listevariables_automatique') }}">Liste des variables(automatique)</a></li>
                                    <li><a href="{{ route('historiques_variables') }}">Historiques des variables</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Missions" class="has-arrow"><i class="icon-users"></i><span>Gestion des autorisations</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-autorisation') }}">Ajouter une autorisation</a></li>
                                    <li><a href="{{ route('listemissions') }}">Liste des missions</a></li>
                                    <li><a href="{{ route('listeautorisations') }}">Liste des autorisations</a></li>
                                    <li><a href="{{ route('ajouterConges') }}">Ajouter un congé</a></li>
                                    <li><a href="{{ route('listeconges') }}">Liste des congés</a></li>
                                    <li><a href="{{ route('calendrier_conges') }}">Calendrier des congés</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#Sanction" class="has-arrow"><i class="icon-users"></i><span>Gestion des sanctions</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-sanction') }}">Ajouter une sanction</a></li>
                                    <li><a href="{{ route('listesanctions') }}">Liste des sanctions</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#Tenues" class="has-arrow"><i class="icon-users"></i><span>Gestion des tenues</span></a>
                                <ul>
                                    <li><a href="{{ url('ajouter-tenue') }}">Ajouter une tenue</a></li>
                                    <li><a href="{{ route('listetenues') }}">Liste des tenues</a></li>
                                    <li><a href="{{ route('appro_stock') }}">Approvisionner le stock</a></li>
                                    <li><a href="{{ route('stock_tenues') }}">Stock des tenues</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#Sante" class="has-arrow"><i class="icon-users"></i><span>Espace santé</span></a>
                                <ul>
                                    <li><a href="{{ route('listesaccident') }}">Liste accident de travail</a></li>
                                    <li><a href="{{ route('listesconsultation') }}">Liste des consultations</a></li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane animated fadeIn" id="sub_menu">
                    @if((Auth::user()?->idrole == 1))
                        <nav class="sidebar-nav">
                            <ul class="main-menu metismenu">

                                <li>
                                    <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Autres travailleurs</span></a>
                                    <ul>
                                        <li><a href="{{ url('sanctions-autres-travailleur') }}">Sanctions</a></li>
                                        <li><a href="{{ url('ajouter-autres-travailleur') }}">Recrutement</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Mon profil</span></a>
                                    <ul>
                                        <li><a href="#">Information personnel</a></li>
                                        <li><a href="#">Paramètre de connexion</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#FileManager" class="has-arrow"><i class="icon-folder"></i> <span>Setting</span></a>
                                    <ul>
                                        <li><a href="{{ route('unites') }}">Unité</a></li>
                                        <li><a href="{{ route('departements') }}">Département</a></li>
                                        <li><a href="{{ route('equipes') }}">Équipe</a></li>
                                        <li><a href="{{ route('niveauEtude') }}">Niveau d'étude</a></li>
                                        <li><a href="{{ route('categories') }}">Catégorie</a></li>
                                        <li><a href="{{ route('fonctions') }}">Fonction</a></li>
                                        <li><a href="{{ route('pays') }}">Pays</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </nav>
                    @endif
                </div>
                <div class="tab-pane animated fadeIn" id="setting">
                    <div class="p-l-15 p-r-15">
                        <h6>Choose Skin</h6>
                        <ul class="choose-skin list-unstyled">
                            <li data-theme="purple">
                                <div class="purple"></div>
                                <span>Purple</span>
                            </li>
                            <li data-theme="blue">
                                <div class="blue"></div>
                                <span>Blue</span>
                            </li>
                            <li data-theme="cyan">
                                <div class="cyan"></div>
                                <span>Cyan</span>
                            </li>
                            <li data-theme="green">
                                <div class="green"></div>
                                <span>Green</span>
                            </li>
                            <li data-theme="orange" class="active">
                                <div class="orange"></div>
                                <span>Orange</span>
                            </li>
                            <li data-theme="blush">
                                <div class="blush"></div>
                                <span>Blush</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif


    </div>
</div>