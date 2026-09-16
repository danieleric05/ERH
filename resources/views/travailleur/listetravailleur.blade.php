@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des travailleurs actifs</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste des travailleurs</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('liste_travailleurs') }}"
           class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition text-sm font-medium">
            <i class="fa fa-users"></i> Tous les travailleurs
        </a>
        <a href="{{ url('liste-embauches') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
            <i class="fa fa-user-plus"></i> Embauchés
        </a>
        <a href="{{ route('liste_declarations') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
            <i class="fa fa-file-text"></i> Non déclarés
        </a>
        <a href="{{ route('liste_cessations') }}"
           class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
            <i class="fa fa-ban"></i> En cessations
        </a>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

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

    <!-- Search Form + taille de page -->
    <div class="mb-6 flex justify-end items-center gap-3">
        <form method="GET">
            <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
                @foreach([25, 50, 100, 200] as $n)
                    <option value="{{ $n }}" @selected((int) request('per_page', 50) === $n)>{{ $n }} / page</option>
                @endforeach
            </select>
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
            @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
            @if(request('dir'))<input type="hidden" name="dir" value="{{ request('dir') }}">@endif
        </form>
        <form method="GET" class="flex items-center max-w-lg">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Rechercher par nom, prénom, matricule, identifiant..."
                   class="w-full px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-primary-accent focus:border-primary-accent transition-shadow"
                   autocomplete="off">
            <button type="submit"
                    class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-r-lg hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition-colors">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden"><div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('matricule') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Matricule {!! $sortIcon('matricule') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('nomprnoms') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Nom & Prénoms {!! $sortIcon('nomprnoms') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('datedembauche') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Date d'embauche {!! $sortIcon('datedembauche') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('datefindecontrat') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Date fin de contrat {!! $sortIcon('datefindecontrat') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Etat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody id="travailleursTableBody">
                @forelse($data_travailleurdeux ?? [] as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ $listedata->photo ? asset('rhassets/images/travailleurs/' . $listedata->photo) : asset('rhassets/images/travailleurs/default.png') }}"
                                 height="50" width="50"
                                 class="rounded-full object-cover w-12 h-12"
                                 alt="Photo {{ $listedata->nom }}">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                               title="MODIFIER"
                               :class="$listedata->numero_securite ? 'text-red-600' : 'text-slate-900'"
                               class="font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary"
                            :title="$listedata->numero_securite ? 'DECL. CNPS' : ''">
                            <div>{{ $listedata->nom ?? '' }}</div>
                            <div>{{ $listedata->prenom ?? '' }} {{ $listedata->prenom_suite ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- Cette liste est déjà filtrée par Travailleur::actif() côté contrôleur :
                                 toute ligne affichée ici est active par construction. --}}
                            @if(($listedata->etapeid ?? null) == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">RECONDUIRE</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">ACTIF</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-wrap justify-center gap-1">
                                @if(($listedata->etapeid ?? null) != 3)
                                    <a href="{{ url('action/declaration/travailleurs') }}"
                                       onclick="event.preventDefault(); openDeclarationModal({{ $listedata->id }})"
                                       title="CESSATION/CERTIFICAT/DECLARATION"
                                       data-id="{{ $listedata->id }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       >
                                        <i class="fa fa-map-marker"></i>
                                    </a>
                                @endif

                                @if($listedata->etapeid == 3)
                                    <a href="{{ url('reconduire/journalier') }}"
                                       onclick="event.preventDefault(); openReconduireModal({{ $listedata->id }})"
                                       title="RECONDUIRE LE TRAVAILLEUR"
                                       data-id="{{ $listedata->id }}"
                                       class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition"
                                       >
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                @endif

                                @if($listedata->etapeid == 2)
                                    @if($listedata->idtype_contrat == 2)
                                        <a href="{{ route('telechargerContratCDD', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDD"
                                           class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @elseif($listedata->idtype_contrat == 3)
                                        <a href="{{ route('telechargerContratCDI', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDI"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('telechargerContratJournalier', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT JOURNALIER"
                                           class="p-2 text-lime-600 hover:bg-lime-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @endif
                                @endif

                                @if($listedata->etapeid == 6)
                                    @if($listedata->idtype_contrat == 2)
                                        <a href="{{ route('telechargerContratCDD', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDD"
                                           class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @elseif($listedata->idtype_contrat == 3)
                                        <a href="{{ route('telechargerContratCDI', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDI"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('telechargerContratJournalier', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT JOURNALIER"
                                           class="p-2 text-lime-600 hover:bg-lime-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @endif
                                @endif

                                @if($listedata->etapeid == 5)
                                    <a href="{{ route('telechargerContratDeclarationCnps', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                       target="_blank"
                                       title="DECLARATION CNPS"
                                       class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>

                                    @if($listedata->idtype_contrat == 2)
                                        <a href="{{ route('telechargerContratCDD', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDD"
                                           class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                    @elseif($listedata->idtype_contrat == 3)
                                        <a href="{{ route('telechargerContratCDI', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT CDI"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('telechargerContratJournalier', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                           target="_blank"
                                           title="CONTRAT JOURNALIER"
                                           class="p-2 text-lime-600 hover:bg-lime-50 rounded-lg transition">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                    @endif
                                @endif

                                <a href="{{ route('historiques_contrat', $listedata->id) }}"
                                   title="HISTORIQUES"
                                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                   >
                                    <i class="fa fa-list"></i>
                                </a>

                                <a href="#"
                                   title="DESACTIVER/RETIRER"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"
                                   data-id="{{ $listedata->id }}"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                   >
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">
                            Aucune donnée de travailleur disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $data_travailleurdeux->links() }}
    </div>

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')

@endsection
