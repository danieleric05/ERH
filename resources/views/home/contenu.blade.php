<div class="px-6 py-8">
    {{-- Section d'en-tête (Breadcrumb et Titre) --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-text-primary">
            Bienvenue {{ Auth::user()->name ?? 'Utilisateur' }}
        </h1>
        <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="icon-home"></i></a></li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-text-secondary">Accueil</span>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Contenu spécifique pour les rôles 4 ou 5 (employés réguliers ou visiteurs) --}}
    @if((Auth::user()->idrole == 4) || (Auth::user()->idrole == 5))
    <div class="flex flex-col items-center justify-center bg-white rounded-lg shadow-lg-soft p-16 my-8">
        <img src="{{ asset('rhassets/images/logoo.png') }}" class="w-auto h-72" alt="Logo-Plastica">
        <p class="mt-4 text-xl font-medium text-text-secondary text-center">Votre tableau de bord est en cours de personnalisation.</p>
    </div>
    @endif

    {{-- Contenu spécifique pour le rôle 6 (exemple : Responsable RH Junior) --}}
    @if(Auth::user()->idrole == 6)
    <div class="my-4 overflow-hidden rounded-md bg-yellow-50 p-3 text-sm font-semibold text-red-600">
        <marquee class="py-1"> VOUS AUREZ {{ count($count_fin_contrat ?? []) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES </marquee>
    </div>

    <div class="my-4 bg-blue-100 p-3 text-center font-bold text-blue-800 rounded-md shadow-sm">Mission et Stock tenue</div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-lg-soft border-t-4 border-plastica-blue p-5">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-text-primary">Mission <small class="text-slate-500 block sm:inline-block sm:float-right mt-1 sm:mt-0">Liste des travailleurs ayant rempli la fiche de mission</small></h3>
            </div>
            <div>
                <ul class="divide-y divide-slate-200">
                    {{-- Exemple de données statiques, à remplacer par une boucle si des données sont passées --}}
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Jaures <small class="text-slate-500 float-right">12-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France</p>
                        </div>
                    </li>
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Yves <small class="text-slate-500 float-right">25-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France, Itali</small></p>
                        </div>
                    </li>
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Okou <small class="text-slate-500 float-right">19-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France, Espagne</p>
                        </div>
                    </li>
                </ul>
                <a href="#" class="block text-right mt-4 text-primary-accent hover:text-plastica-blue text-sm font-medium">Voir Plus</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft border-t-4 border-red-500 p-5">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-text-primary">Stock tenues <small class="text-slate-500 block sm:inline-block sm:float-right mt-1 sm:mt-0">Liste des articles ayant atteint le seuil</small></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Article</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">En stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Approvisionné le</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($article ?? [] as $art)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $art?->label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $art->quantite_en_stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $art->date_reception }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <a href="{{ route('stock_tenues') }}" class="block text-right mt-4 text-primary-accent hover:text-plastica-blue text-sm font-medium">Voir Plus</a> --}}
        </div>
    </div>
    @endif

    {{-- Contenu spécifique pour le rôle 2 (Assistante RH) --}}
    @if(Auth::user()->idrole == 2)
    <div class="my-4 overflow-hidden rounded-md bg-yellow-50 p-3 text-sm font-semibold text-red-600">
        <marquee class="py-1"> VOUS AUREZ {{ count($count_fin_contrat ?? []) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES </marquee>
    </div>

    <div class="my-4 bg-red-100 p-3 text-center font-bold text-red-800 rounded-md shadow-sm">Journaliers et embauchés</div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <a href="{{ route('liste_embauches') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-user"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Nombre d'embauchés</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ $data_embauche ?? 0 }}</h5>
                    </div>
                </div>
            </a>
            <hr class="my-4 border-slate-200">
            <a href="{{ route('liste_tous_travailleurs') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-users"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Nombre de journaliers</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ $data_journalier ?? 0 }}</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <a href="{{ route('liste_declarations') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-university"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Journaliers non déclarés</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ count($count_journaler_non_declare ?? []) }}</h5>
                    </div>
                </div>
            </a>
            <hr class="my-4 border-slate-200">
            <a href="{{ url('journaliers-fin-contrat') }}" class="block text-red-600 font-bold hover:text-red-800">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 text-red-600 text-2xl"><i class="fa fa-university"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-red-600">Journaliers en fin de contrat</div>
                        <h5 class="text-xl font-bold text-red-600">{{ count($count_fin_contrat ?? []) }}</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <div class="flex items-center space-x-4 mb-4">
                <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-user"></i></div>
                <a href="{{ route('listesaccident') }}" class="block text-red-600 font-bold hover:text-red-800 flex-1">
                    <div class="text-sm font-medium text-red-600">Accident de travail</div>
                    <h5 class="text-xl font-bold text-red-600">{{ $listeAT ?? 0 }}</h5>
                </a>
            </div>
            <hr class="my-4 border-slate-200">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-users"></i></div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-text-secondary">Consultations sans A.T / avec A.T</div>
                    <h5 class="text-xl font-bold text-text-primary flex items-center space-x-2">
                        <a href="{{ route('listesconsultation') }}">
                            <button type="button" class="px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700 text-xs font-medium">
                                <span>{{ $data_sans_arret ?? 0 }}</span>
                            </button>
                        </a>
                        <a href="{{ route('listesconsultation') }}">
                            <button type="button" class="px-3 py-1 rounded-md text-white bg-primary-accent hover:bg-plastica-blue text-xs font-medium">
                                <span>{{ $data_avec_arret ?? 0 }}</span>
                            </button>
                        </a>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Contenu spécifique pour le rôle 1 (Administrateur) --}}
    @if(Auth::user()->idrole == 1)
    <div class="my-4 overflow-hidden rounded-md bg-yellow-50 p-3 text-sm font-semibold text-red-600">
        <marquee class="py-1"> VOUS AUREZ {{ count($count_fin_contrat ?? []) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES </marquee>
    </div>

    <div class="my-4 bg-red-100 p-3 text-center font-bold text-red-800 rounded-md shadow-sm">Journaliers et embauchés</div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <a href="{{ route('liste_embauches') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-user"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Nombre d'embauchés</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ $data_embauche ?? 0 }}</h5>
                    </div>
                </div>
            </a>
            <hr class="my-4 border-slate-200">
            <a href="{{ route('liste_tous_travailleurs') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-users"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Nombre de journaliers</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ $data_journalier ?? 0 }}</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <a href="{{ route('liste_declarations') }}" class="block hover:opacity-80 transition">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-university"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-text-secondary">Journaliers non déclarés</div>
                        <h5 class="text-xl font-bold text-text-primary">{{ count($count_journaler_non_declare ?? []) }}</h5>
                    </div>
                </div>
            </a>
            <hr class="my-4 border-slate-200">
            <a href="{{ url('journaliers-fin-contrat') }}" class="block text-red-600 font-bold hover:text-red-800">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 text-red-600 text-2xl"><i class="fa fa-university"></i></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-red-600">Journaliers en fin de contrat</div>
                        <h5 class="text-xl font-bold text-red-600">{{ count($count_fin_contrat ?? []) }}</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft p-5 border-t-4 border-plastica-blue">
            <div class="flex items-center space-x-4 mb-4">
                <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-user"></i></div>
                <a href="{{ route('listesaccident') }}" class="block text-red-600 font-bold hover:text-red-800 flex-1">
                    <div class="text-sm font-medium text-red-600">Accident de travail</div>
                    <h5 class="text-xl font-bold text-red-600">{{ $listeAT ?? 0 }}</h5>
                </a>
            </div>
            <hr class="my-4 border-slate-200">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 text-plastica-blue text-2xl"><i class="fa fa-users"></i></div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-text-secondary">Consultations sans A.T / avec A.T</div>
                    <h5 class="text-xl font-bold text-text-primary flex items-center space-x-2">
                        <a href="{{ route('listesconsultation') }}">
                            <button type="button" class="px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700 text-xs font-medium">
                                <span>{{ $data_sans_arret ?? 0 }}</span>
                            </button>
                        </a>
                        <a href="{{ route('listesconsultation') }}">
                            <button type="button" class="px-3 py-1 rounded-md text-white bg-primary-accent hover:bg-plastica-blue text-xs font-medium">
                                <span>{{ $data_avec_arret ?? 0 }}</span>
                            </button>
                        </a>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="my-8 bg-blue-100 p-3 text-center font-bold text-blue-800 rounded-md shadow-sm">Mois en cours</div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <div class="text-center">
            <a href="{{ route('listesanctions') }}">
                <div class="bg-orange-50 rounded-lg shadow-lg-soft border-t-4 border-slate-900 p-5 h-full flex flex-col justify-between hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center mb-4">
                        <div class="rounded-full h-24 w-24 bg-slate-900 flex items-center justify-center mx-auto">
                            <i class="fa fa-exclamation-triangle text-white text-5xl"></i>
                        </div>
                    </div>
                    <h6 class="mt-4 text-lg font-semibold text-slate-900">Gestion des Sanctions</h6>
                    <p class="text-base text-text-secondary mt-2">{{ $data_sanctions ?? 0 }} <i class="zmdi zmdi-trending-up"></i></p>
                </div>
            </a>
        </div>

        <div class="text-center">
            <a href="{{ url('variables') }}">
                <div class="bg-red-100 rounded-lg shadow-lg-soft border-t-4 border-red-500 p-5 h-full flex flex-col justify-between hover:shadow-xl transition-shadow duration-300">
                    <div class="text-center mb-4">
                        <div class="rounded-full h-24 w-24 bg-red-500 flex items-center justify-center mx-auto">
                            <i class="fa fa-calculator text-white text-5xl"></i>
                        </div>
                    </div>
                    <h6 class="mt-4 text-lg font-semibold text-red-600">Gestion Variables</h6>
                    <p class="text-base text-text-secondary mt-2">{{ $data_variables ?? 0 }}</p>
                </div>
            </a>
        </div>

        <div class="text-center">
            <div class="bg-blue-100 rounded-lg shadow-lg-soft border-t-4 border-plastica-blue p-5 h-full flex flex-col justify-between opacity-75 cursor-not-allowed">
                <div class="text-center mb-4">
                    <div class="rounded-full h-24 w-24 bg-plastica-blue flex items-center justify-center mx-auto">
                        <i class="fa fa-file-text text-white text-5xl"></i>
                    </div>
                </div>
                <h6 class="mt-4 text-lg font-semibold text-primary-accent">Gestion des autorisations</h6>
                <p class="text-base text-text-secondary mt-2">{{ $data_autorisation ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-1">(Fonctionnalité en cours)</p>
            </div>
        </div>

        {{-- Section Précarité archivée - Commentée car fonctionnalité obsolète --}}
        {{--
        <div class="text-center">
            <div class="bg-green-100 rounded-lg shadow-lg-soft border-t-4 border-green-600 p-5 h-full flex flex-col justify-between">
                <div class="body text-center mb-4">
                    <div class="rounded-full h-24 w-24 bg-green-600 flex items-center justify-center mx-auto">
                        <i class="fa fa-money text-white text-5xl"></i>
                    </div>
                </div>
                <h6 class="mt-4 text-lg font-semibold text-green-700">Précarité a payer</h6>
                <p class="text-base text-text-secondary mt-2">
                    @php
                    $calcul = 0;
                    foreach ($data_precarite ?? [] as $precarite){
                    $calcul += 2768 + 176 * 0.03 * ($precarite->valeur ?? 0);
                    }
                    echo $calcul.' FCFA';
                    @endphp
                </p>
            </div>
        </div>
        --}}
    </div>

    <div class="my-8 bg-blue-100 p-3 text-center font-bold text-blue-800 rounded-md shadow-sm">Mission et Stock tenue</div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-lg-soft border-t-4 border-plastica-blue p-5">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-text-primary">Mission <small class="text-slate-500 block sm:inline-block sm:float-right mt-1 sm:mt-0">Liste des travailleurs ayant rempli la fiche de mission</small></h3>
            </div>
            <div class="p-5">
                <ul class="divide-y divide-slate-200">
                    {{-- Exemple de données statiques, à remplacer par une boucle si des données sont passées --}}
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Jaures <small class="text-slate-500 float-right">12-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France</p>
                        </div>
                    </li>
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Yves <small class="text-slate-500 float-right">25-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France, Itali</small></p>
                        </div>
                    </li>
                    <li class="py-3 flex items-center">
                        <div class="flex-shrink-0 mr-3 text-plastica-blue"><i class="fa fa-user"></i></div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-text-primary">Okou Okou <small class="text-slate-500 float-right">19-04-2019 10:45</small></h4>
                            <p class="text-sm text-text-secondary">Pays/Villes : Dubai, France, Espagne</p>
                        </div>
                    </li>
                </ul>
                <a href="#" class="block text-right mt-4 text-primary-accent hover:text-plastica-blue text-sm font-medium">Voir Plus</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg-soft border-t-4 border-red-500 p-5">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-text-primary">Stock tenues <small class="text-slate-500 block sm:inline-block sm:float-right mt-1 sm:mt-0">Liste des articles ayant atteint le seuil</small></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Article</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">En stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Approvisionné le</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($article ?? [] as $art)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $art?->label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $art->quantite_en_stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $art->date_reception }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <a href="{{ route('stock_tenues') }}" class="block text-right mt-4 text-primary-accent hover:text-plastica-blue text-sm font-medium">Voir Plus</a> --}}
        </div>
    </div>
    @endif
</div>
<script>
function tableSort() {
    return {
        sortBy: null,
        sortDir: 'asc',

        sort(column) {
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc';
            }
            this.sortTable();
        },

        sortTable() {
            const tbody = document.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const headers = document.querySelectorAll('thead th');
                let colIndex = 0;
                
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].textContent.toLowerCase().includes(this.sortBy.toLowerCase())) {
                        colIndex = i;
                        break;
                    }
                }

                const cellA = a.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                const cellB = b.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                
                if (!cellA || !cellB) return 0;

                let valueA = cellA.textContent.trim();
                let valueB = cellB.textContent.trim();

                const dateA = new Date(valueA).getTime();
                const dateB = new Date(valueB).getTime();

                if (!isNaN(dateA) && !isNaN(dateB) && dateA > 0 && dateB > 0) {
                    return this.sortDir === 'asc' ? dateA - dateB : dateB - dateA;
                }

                return this.sortDir === 'asc'
                    ? String(valueA).localeCompare(String(valueB), 'fr-FR')
                    : String(valueB).localeCompare(String(valueA), 'fr-FR');
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    }
}
</script>
