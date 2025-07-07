<aside class="admin-sidebar">
    <div class="admin-sidebar-brand">
        <!-- begin sidebar branding-->
        <img class="admin-brand-logo" src="{{ asset('assetRH/img/logo.png') }}" width="50" alt="Okou Jaures">
        <!-- end sidebar branding-->
        <div class="ml-auto">
            <!-- sidebar pin-->
            <a href="#" class="admin-pin-sidebar btn-ghost btn btn-rounded-circle"></a>
            <!-- sidebar close for mobile device-->
            <a href="#" class="admin-close-sidebar"></a>
        </div>
    </div>
    <div class="admin-sidebar-wrapper js-scrollbar">
        <ul class="menu">
            <li class="menu-item active ">
                <a href="#" class="open-dropdown menu-link">
                        <span class="menu-label">
                            <span class="menu-name">Bienvenue</span>
                        </span>
                        <span class="menu-icon">
                             <i class="icon-placeholder fe fe-home "></i>
                        </span>
                </a>
            </li>
            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Recrutement
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-activity "></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='{{ url('inscription-ouvrier') }}' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Ajouter un embauché
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-checkbook "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='{{ url('liste-travailleurs') }}' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Liste des embauchés inscrits
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-checkbook "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='{{ url('liste-travailleurs') }}' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Liste complète des embauchés
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-checkbook "></i>
                            </span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href='{{ url('liste-travailleurs') }}' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Renouveler un contrat
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-checkbook "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Variables
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-bar-chart-2 "></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Variables 1
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Variables 2
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Gestion des missions
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-clipboard"></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Ajouter une mission
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">
                                    Liste des missions des embauchés
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Gestion des congés
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-crosshair"></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Ajouter un congé
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">
                                    Liste des congés des embauchés
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Sanction
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-briefcase"></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Ajouter une sanction
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">
                                    Liste des sanctions des embauchés
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Consultations
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-bar-chart-2 "></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Consultation
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Accident de travail
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">
                                    Liste des accidents de travail
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item ">
                <a href="#" class="open-dropdown menu-link">
                    <span class="menu-label">
                        <span class="menu-name">Configuration
                            <span class="menu-arrow"></span>
                        </span>
                    </span>
                    <span class="menu-icon">
                         <i class="icon-placeholder fe fe-settings "></i>
                    </span>
                </a>
                <!--submenu-->
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href='{{ route('departements') }}' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Departement
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Equipe
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Pays
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Niveau d'etude
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href='#' class=' menu-link'>
                            <span class="menu-label">
                                <span class="menu-name">Fonction
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-chart-gantt "></i>
                            </span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item active ">
                <a href="#" class="open-dropdown menu-link">
                        <span class="menu-label">
                            <span class="menu-name">Mon Profil</span>
                        </span>
                    <span class="menu-icon">
                             <i class="icon-placeholder fe fe-user "></i>
                        </span>
                </a>
            </li>

        </ul>

    </div>

</aside>