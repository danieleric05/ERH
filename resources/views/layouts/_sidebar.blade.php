<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-white text-slate-800 border-r border-slate-200 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:block"
       :class="{ '-translate-x-full': !$root.sidebarOpen, 'translate-x-0': $root.sidebarOpen }"
       x-data="{
           openMenus: {
               recrutement: false,
               precarites: false,
               variables: false,
               autorisations: false,
               conges: false,
               sanctions: false,
               tenues: false,
               sante: false,
               espaceSante: false,
               utilisateurs: false
           }
       }">
    <div class="flex items-center justify-center h-16 bg-slate-50 border-b border-slate-200 shadow-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <span class="text-plastica-blue font-bold uppercase text-lg">ERH</span>
        </a>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2">

        {{-- Dashboard (tous les rôles) --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors
                  {{ request()->routeIs('dashboard', 'bienvenue') ? 'bg-slate-100 font-semibold text-plastica-blue' : '' }}">
            <i class="fa fa-home mr-3 w-5"></i>
            Dashboard
        </a>

        {{-- RÔLE 1 : ADMINISTRATEUR --}}
        @if(Auth::user()->idrole == 1)

            {{-- Recrutement Section --}}
            <div class="pt-4">
                <button @click="openMenus.recrutement = !openMenus.recrutement"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-briefcase mr-3 w-5"></i>
                        <span>Recrutement</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.recrutement}"></i>
                </button>
                <div x-show="openMenus.recrutement" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-travailleur-etape-un') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Recrutement 1ère Étape
                    </a>
                    <a href="{{ route('listetravailleurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Recrutement 2ème Étape
                    </a>
                    <a href="{{ route('liste_tous_travailleurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-users mr-3 w-4"></i>
                        Liste des travailleurs
                    </a>
                    <a href="{{ route('liste_cessations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-ban mr-3 w-4"></i>
                        Liste des cessations
                    </a>
                    <a href="{{ route('liste_certificat_travail') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-certificate mr-3 w-4"></i>
                        Liste des certificats
                    </a>
                    <a href="{{ route('liste_declarations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-file-text mr-3 w-4"></i>
                        Liste des déclarations
                    </a>
                    <a href="{{ route('journaliers_fin_contrat') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-exclamation-triangle mr-3 w-4"></i>
                        Contrats bientôt expirés
                    </a>
                    <a href="{{ route('historiques') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-search mr-3 w-4"></i>
                        Rechercher
                    </a>
                </div>
            </div>

            {{-- ========================================
                 ARCHIVÉ - Gestion des Précarités
                 Plus d'actualité - Pour réactiver : décommenter cette section
                 ======================================== --}}
            {{-- <div class="pt-2">
                <button @click="openMenus.precarites = !openMenus.precarites"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-money mr-3 w-5"></i>
                        <span>Gestion des Précarités</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.precarites}"></i>
                </button>
                <div x-show="openMenus.precarites" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-ha01') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-upload mr-3 w-4"></i>
                        Importer HA01
                    </a>
                    <a href="{{ route('historique_ha01') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-history mr-3 w-4"></i>
                        Historique HA01
                    </a>
                    <a href="{{ route('listeprecarites') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des précarités
                    </a>
                </div>
            </div> --}}

            {{-- Gestion des Variables Section --}}
            <div class="pt-2">
                <button @click="openMenus.variables = !openMenus.variables"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-calculator mr-3 w-5"></i>
                        <span>Gestion des Variables</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.variables}"></i>
                </button>
                <div x-show="openMenus.variables" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-variable') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter variable
                    </a>
                    <a href="{{ url('ajouter-heure-supplementaire') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Heure supplémentaire
                    </a>
                    <a href="{{ url('ajouter-autres-variables') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Autres variables
                    </a>
                    <a href="{{ route('listevariables_manuelle') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Variables manuelles
                    </a>
                    <a href="{{ route('listevariables_heure_supp') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Heures supplémentaires
                    </a>
                    <a href="{{ route('listevariables_automatique') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Variables automatiques
                    </a>
                    <a href="{{ route('historiques_variables') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-history mr-3 w-4"></i>
                        Historiques variables
                    </a>
                </div>
            </div>

            {{-- Gestion des Autorisations Section --}}
            <div class="pt-2">
                <button @click="openMenus.autorisations = !openMenus.autorisations"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-calendar mr-3 w-5"></i>
                        <span>Gestion des autorisations</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.autorisations}"></i>
                </button>
                <div x-show="openMenus.autorisations" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-autorisation') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter autorisation
                    </a>
                    <a href="{{ route('listemissions') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des missions
                    </a>
                    <a href="{{ route('listeautorisations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des autorisations
                    </a>
                    <a href="{{ route('calendrier_conges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-calendar mr-3 w-4"></i>
                        Calendrier
                    </a>
                </div>
            </div>

            {{-- Gestion des Congés Section --}}
            <div class="pt-2">
                <button @click="openMenus.conges = !openMenus.conges"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-suitcase mr-3 w-5"></i>
                        <span>Gestion des congés</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.conges}"></i>
                </button>
                <div x-show="openMenus.conges" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('ajouterConges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter un congé
                    </a>
                    <a href="{{ route('listeconges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des congés
                    </a>
                </div>
            </div>

            {{-- Gestion des Sanctions Section --}}
            <div class="pt-2">
                <button @click="openMenus.sanctions = !openMenus.sanctions"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-gavel mr-3 w-5"></i>
                        <span>Gestion des sanctions</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.sanctions}"></i>
                </button>
                <div x-show="openMenus.sanctions" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-sanction') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter sanction
                    </a>
                    <a href="{{ route('listesanctions') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des sanctions
                    </a>
                </div>
            </div>

            {{-- ========================================
                 ARCHIVÉ - Gestion des Tenues
                 Plus d'actualité - Pour réactiver : décommenter cette section
                 ======================================== --}}
            {{-- <div class="pt-2">
                <button @click="openMenus.tenues = !openMenus.tenues"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-shopping-bag mr-3 w-5"></i>
                        <span>Gestion des tenues</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.tenues}"></i>
                </button>
                <div x-show="openMenus.tenues" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-tenue') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter tenue
                    </a>
                    <a href="{{ route('listetenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des tenues
                    </a>
                    <a href="{{ route('appro_stock') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-cube mr-3 w-4"></i>
                        Approvisionner stock
                    </a>
                    <a href="{{ route('stock_tenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-building mr-3 w-4"></i>
                        Stock des tenues
                    </a>
                </div>
            </div> --}}

            {{-- Espace Santé Section --}}
            <div class="pt-2">
                <button @click="openMenus.sante = !openMenus.sante"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-hospital mr-3 w-5"></i>
                        <span>Espace santé</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.sante}"></i>
                </button>
                <div x-show="openMenus.sante" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('listesaccident') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-ambulance mr-3 w-4"></i>
                        Accidents de travail
                    </a>
                    <a href="{{ route('listesconsultation') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-stethoscope mr-3 w-4"></i>
                        Consultations
                    </a>
                </div>
            </div>

            {{-- Administration Section --}}
            <div class="pt-2">
                <button @click="openMenus.utilisateurs = !openMenus.utilisateurs"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-users-cog mr-3 w-5"></i>
                        <span>Administration</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.utilisateurs}"></i>
                </button>
                <div x-show="openMenus.utilisateurs" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('listeutilisateurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-users mr-3 w-4"></i>
                        Gestion des utilisateurs
                    </a>
                </div>
            </div>

        {{-- RÔLE 2 : ASSISTANTE RH --}}
        @elseif(Auth::user()->idrole == 2)

            {{-- Recrutement Section (identique à Rôle 1) --}}
            <div class="pt-4">
                <button @click="openMenus.recrutement = !openMenus.recrutement"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-briefcase mr-3 w-5"></i>
                        <span>Recrutement</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.recrutement}"></i>
                </button>
                <div x-show="openMenus.recrutement" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-travailleur-etape-un') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Recrutement 1ère Étape
                    </a>
                    <a href="{{ route('listetravailleurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Recrutement 2ème Étape
                    </a>
                    <a href="{{ route('liste_tous_travailleurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-users mr-3 w-4"></i>
                        Liste des travailleurs
                    </a>
                    <a href="{{ route('liste_cessations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-ban mr-3 w-4"></i>
                        Liste des cessations
                    </a>
                    <a href="{{ route('liste_certificat_travail') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-certificate mr-3 w-4"></i>
                        Liste des certificats
                    </a>
                    <a href="{{ route('liste_declarations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-file-text mr-3 w-4"></i>
                        Liste des déclarations
                    </a>
                    <a href="{{ route('journaliers_fin_contrat') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-exclamation-triangle mr-3 w-4"></i>
                        Contrats bientôt expirés
                    </a>
                    <a href="{{ route('historiques') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-search mr-3 w-4"></i>
                        Rechercher
                    </a>
                </div>
            </div>

            {{-- ========================================
                 ARCHIVÉ - Gestion des Précarités
                 Plus d'actualité - Pour réactiver : décommenter cette section
                 ======================================== --}}
            {{-- <div class="pt-2">
                <button @click="openMenus.precarites = !openMenus.precarites"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-money mr-3 w-5"></i>
                        <span>Gestion des Précarités</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.precarites}"></i>
                </button>
                <div x-show="openMenus.precarites" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-ha01') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-upload mr-3 w-4"></i>
                        Importer HA01
                    </a>
                    <a href="{{ route('historique_ha01') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-history mr-3 w-4"></i>
                        Historique HA01
                    </a>
                    <a href="{{ route('listeprecarites') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des précarités
                    </a>
                </div>
            </div> --}}

            {{-- Gestion des Variables Section --}}
            <div class="pt-2">
                <button @click="openMenus.variables = !openMenus.variables"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-calculator mr-3 w-5"></i>
                        <span>Gestion des Variables</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.variables}"></i>
                </button>
                <div x-show="openMenus.variables" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-variable') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter variable
                    </a>
                    <a href="{{ url('ajouter-heure-supplementaire') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Heure supplémentaire
                    </a>
                    <a href="{{ url('ajouter-autres-variables') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Autres variables
                    </a>
                    <a href="{{ route('listevariables_manuelle') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Variables manuelles
                    </a>
                    <a href="{{ route('listevariables_heure_supp') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Heures supplémentaires
                    </a>
                    <a href="{{ route('listevariables_automatique') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Variables automatiques
                    </a>
                    <a href="{{ route('historiques_variables') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-history mr-3 w-4"></i>
                        Historiques variables
                    </a>
                </div>
            </div>

            {{-- Gestion des Autorisations Section --}}
            <div class="pt-2">
                <button @click="openMenus.autorisations = !openMenus.autorisations"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-calendar mr-3 w-5"></i>
                        <span>Gestion des autorisations</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.autorisations}"></i>
                </button>
                <div x-show="openMenus.autorisations" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-autorisation') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter autorisation
                    </a>
                    <a href="{{ route('listemissions') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des missions
                    </a>
                    <a href="{{ route('listeautorisations') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des autorisations
                    </a>
                    <a href="{{ route('calendrier_conges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-calendar mr-3 w-4"></i>
                        Calendrier
                    </a>
                </div>
            </div>

            {{-- Gestion des Congés Section --}}
            <div class="pt-2">
                <button @click="openMenus.conges = !openMenus.conges"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-suitcase mr-3 w-5"></i>
                        <span>Gestion des congés</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.conges}"></i>
                </button>
                <div x-show="openMenus.conges" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('ajouterConges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter un congé
                    </a>
                    <a href="{{ route('listeconges') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des congés
                    </a>
                </div>
            </div>

            {{-- Gestion des Sanctions Section --}}
            <div class="pt-2">
                <button @click="openMenus.sanctions = !openMenus.sanctions"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-gavel mr-3 w-5"></i>
                        <span>Gestion des sanctions</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.sanctions}"></i>
                </button>
                <div x-show="openMenus.sanctions" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-sanction') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter sanction
                    </a>
                    <a href="{{ route('listesanctions') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des sanctions
                    </a>
                </div>
            </div>

            {{-- ========================================
                 ARCHIVÉ - Gestion des Tenues
                 Plus d'actualité - Pour réactiver : décommenter cette section
                 ======================================== --}}
            {{-- <div class="pt-2">
                <button @click="openMenus.tenues = !openMenus.tenues"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-shopping-bag mr-3 w-5"></i>
                        <span>Gestion des tenues</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.tenues}"></i>
                </button>
                <div x-show="openMenus.tenues" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-tenue') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Ajouter tenue
                    </a>
                    <a href="{{ route('listetenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des tenues
                    </a>
                    <a href="{{ route('appro_stock') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-cube mr-3 w-4"></i>
                        Approvisionner stock
                    </a>
                    <a href="{{ route('stock_tenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-building mr-3 w-4"></i>
                        Stock des tenues
                    </a>
                </div>
            </div> --}}

            {{-- Espace Santé Section --}}
            <div class="pt-2">
                <button @click="openMenus.sante = !openMenus.sante"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-hospital mr-3 w-5"></i>
                        <span>Espace santé</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.sante}"></i>
                </button>
                <div x-show="openMenus.sante" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('listesaccident') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-ambulance mr-3 w-4"></i>
                        Accidents de travail
                    </a>
                    <a href="{{ route('listesconsultation') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-stethoscope mr-3 w-4"></i>
                        Consultations
                    </a>
                </div>
            </div>

            {{-- Administration Section --}}
            <div class="pt-2">
                <button @click="openMenus.utilisateurs = !openMenus.utilisateurs"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-users-cog mr-3 w-5"></i>
                        <span>Administration</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.utilisateurs}"></i>
                </button>
                <div x-show="openMenus.utilisateurs" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('listeutilisateurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-users mr-3 w-4"></i>
                        Gestion des utilisateurs
                    </a>
                </div>
            </div>

        {{-- RÔLE 4 : MÉDICAL ou RÔLE 5 : VISITEUR --}}
        @elseif(in_array(Auth::user()->idrole, [4, 5]))

            {{-- Espace Santé Section --}}
            <div class="pt-4">
                <button @click="openMenus.espaçeSanté = !openMenus.espaçeSanté"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-hospital mr-3 w-5"></i>
                        <span>Espace Santé</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.espaçeSanté}"></i>
                </button>
                <div x-show="openMenus.espaçeSanté" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="/liste-consultations" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-stethoscope mr-3 w-4"></i>
                        Consultations
                    </a>
                    <a href="/liste-accident-travail" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-ambulance mr-3 w-4"></i>
                        Accidents
                    </a>
                </div>
            </div>

        {{-- RÔLE 6 : RH JUNIOR --}}
        @elseif(Auth::user()->idrole == 6)

            {{-- Recrutement Section --}}
            <div class="pt-4">
                <button @click="openMenus.recrutement = !openMenus.recrutement"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-briefcase mr-3 w-5"></i>
                        <span>Recrutement</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.recrutement}"></i>
                </button>
                <div x-show="openMenus.recrutement" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ route('liste_tous_travailleurs') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-users mr-3 w-4"></i>
                        Liste des travailleurs
                    </a>
                </div>
            </div>

            {{-- ========================================
                 ARCHIVÉ - Gestion des Tenues
                 Routes commentées dans web.php (post_gestion_tenue, listetenues, stock_tenues, appro_stock)
                 Pour réactiver : décommenter les routes dans web.php ET cette section
                 ======================================== --}}
            {{-- <div class="pt-2">
                <button @click="openMenus.tenues = !openMenus.tenues"
                        class="w-full flex items-center justify-between px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <div class="flex items-center">
                        <i class="fa fa-shopping-bag mr-3 w-5"></i>
                        <span>Gestion des tenues</span>
                    </div>
                    <i class="fa fa-chevron-down transition-transform duration-300" :class="{'rotate-180': openMenus.tenues}"></i>
                </button>
                <div x-show="openMenus.tenues" x-transition class="ml-4 space-y-1 mt-2">
                    <a href="{{ url('ajouter-tenue') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-plus mr-3 w-4"></i>
                        Attribuer une tenue
                    </a>
                    <a href="{{ route('listetenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-list mr-3 w-4"></i>
                        Liste des tenues attribuées
                    </a>
                    <a href="{{ route('appro_stock') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-cube mr-3 w-4"></i>
                        Approvisionner le stock
                    </a>
                    <a href="{{ route('stock_tenues') }}" class="flex items-center px-4 py-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors text-sm">
                        <i class="fa fa-building mr-3 w-4"></i>
                        Stock des tenues
                    </a>
                </div>
            </div> --}}

        @endif

        {{-- Profil (tous les rôles) --}}
        <div class="pt-4 border-t border-slate-200 mt-4">
            <a href="{{ route('mon-profil') }}" class="flex items-center px-4 py-2 text-slate-700 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fa fa-user mr-3 w-5"></i>
                Mon Profil
            </a>
        </div>

    </nav>
</aside>
