@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- En-tête avec Breadcrumb et Titre --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Liste des travailleurs actifs</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Recrutement</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Messages --}}
        @include('success')
        @include('errors')

        {{-- Boutons de navigation --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('liste_travailleurs') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800 transition-colors">
                <i class="fa fa-users mr-2"></i> Tous les travailleurs
            </a>
            <a href="{{ url('liste-embauches') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition-colors">
                <i class="fa fa-user-plus mr-2"></i> Travailleurs embauchés
            </a>
            <a href="{{ route('liste_cessations') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition-colors">
                <i class="fa fa-ban mr-2"></i> Cessations
            </a>
            <a href="{{ route('liste_declarations') }}" class="inline-flex items-center px-4 py-2 bg-primary-accent border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-plastica-blue transition-colors">
                <i class="fa fa-exclamation-circle mr-2"></i> Non déclarés
            </a>
        </div>

        @php
            $sortLink = fn($col) => request()->fullUrlWithQuery([
                'sort' => $col,
                'dir' => (request('sort') === $col && request('dir') === 'asc') ? 'desc' : 'asc',
                'page' => 1,
            ]);
            $sortIcon = fn($col) => request('sort') === $col
                ? '<i class="fa fa-arrow-' . (request('dir') === 'asc' ? 'up' : 'down') . ' text-xs"></i>'
                : '';
        @endphp

        {{-- Search Form + taille de page --}}
        <div class="mb-6 flex justify-end items-center gap-3">
            <form method="GET" action="{{ route('liste_travailleurs') }}">
                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    @foreach([25, 50, 100, 200] as $n)
                        <option value="{{ $n }}" @selected((int) request('per_page', 50) === $n)>{{ $n }} / page</option>
                    @endforeach
                </select>
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                @if(request('dir'))<input type="hidden" name="dir" value="{{ request('dir') }}">@endif
            </form>
            <form action="{{ route('liste_travailleurs') }}" method="GET" class="flex items-center max-w-lg">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom, matricule..." class="w-full px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-primary-accent focus:border-primary-accent transition-shadow" autocomplete="off">
                <button type="submit" class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-r-lg hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition-colors">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        {{-- Tableau --}}
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200" id="travailleursTable">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <a href="{{ $sortLink('matricule') }}" class="flex items-center gap-2 hover:text-slate-900">
                                    Matricule {!! $sortIcon('matricule') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <a href="{{ $sortLink('nom') }}" class="flex items-center gap-2 hover:text-slate-900">
                                    Nom & Prénoms {!! $sortIcon('nom') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <a href="{{ $sortLink('embauche') }}" class="flex items-center gap-2 hover:text-slate-900">
                                    Embauche {!! $sortIcon('embauche') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <a href="{{ $sortLink('fin') }}" class="flex items-center gap-2 hover:text-slate-900">
                                    Fin contrat {!! $sortIcon('fin') !!}
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">État</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200" id="travailleursTableBody">
                        @forelse($data_Travailleur ?? [] as $listedata)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $listedata->photo ? asset('rhassets/images/travailleurs/' . $listedata->photo) : asset('rhassets/images/travailleurs/default.png') }}"
                                         height="40" width="40"
                                         class="rounded-full object-cover"
                                         alt="Photo {{ $listedata->nom }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('etapedeuxtravailleur', $listedata->id ?? '') }}" class="text-primary-accent hover:text-plastica-blue font-bold">
                                        {{ $listedata->matricule ?? '-' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                    {{ ($listedata->nom ?? '') }} {{ ($listedata->prenom ?? '') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="text-red-600 font-semibold">{{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $estCesseReel = ($listedata->etapeid ?? null) == 3
                                            && ($listedata->date_debut_contrat ?? null) <= '2024-12-31'
                                            && (($listedata->date_fin_contrat ?? null) < '2025-01-01' || in_array($listedata->id, $cessesReels ?? []));
                                    @endphp
                                    @if(($listedata->etapeid ?? null) == 4)
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Certificat</span>
                                    @elseif($estCesseReel)
                                        <span class="px-2 py-1 text-xs font-semibold text-slate-800 bg-slate-200 rounded-full">Cessation</span>
                                    @elseif(($listedata->etapeid ?? null) == 6)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Reconduit</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Actif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Download Contract Button --}}
                                        @if(($listedata->idtype_contrat ?? 0) == 1)
                                            <a href="{{ route('telechargerContratJournalier', ['id' => $listedata->id ?? '', 'download' => 'pdf']) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-slate-100 transition-colors" title="Télécharger contrat journalier">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>
                                        @elseif(($listedata->idtype_contrat ?? 0) == 2)
                                            <a href="{{ route('telechargerContratCDD', ['id' => $listedata->id ?? '', 'download' => 'pdf']) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-slate-100 transition-colors" title="Télécharger contrat CDD">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>
                                        @elseif(($listedata->idtype_contrat ?? 0) == 3)
                                            <a href="{{ route('telechargerContratCDI', ['id' => $listedata->id ?? '', 'download' => 'pdf']) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-slate-100 transition-colors" title="Télécharger contrat CDI">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>
                                        @endif

                                        {{-- Edit Button --}}
                                        <a href="{{ route('etapedeuxtravailleur', $listedata->id ?? '') }}" class="text-primary-accent hover:text-plastica-blue p-2 rounded-full hover:bg-slate-100 transition-colors" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')" href="{{ url('delete/travailleur') }}?id={{ $listedata->id ?? '' }}" class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-slate-100 transition-colors" title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucun travailleur trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $data_Travailleur->links() }}
        </div>
    </div>

@endsection
