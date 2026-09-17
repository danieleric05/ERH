@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des embauchés</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Embauchés</span>
                </nav>
            </div>
        </div>
    </div>

    @include('travailleur._tabs')

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
        $equipesById = \App\Equipes::whereIn('id', collect($data_journalier ?? [])->pluck('equipeid'))->get()->keyBy('id');
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom, matricule..." class="w-full px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-primary-accent focus:border-primary-accent transition-shadow" autocomplete="off">
            <button type="submit" class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-r-lg hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition-colors">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
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
                            <a href="{{ $sortLink('nom') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Nom & Prénoms {!! $sortIcon('nom') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Équipe</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('embauche') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Date d'embauche {!! $sortIcon('embauche') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            <a href="{{ $sortLink('fin') }}" class="flex items-center gap-2 hover:text-slate-300">
                                Date fin de contrat {!! $sortIcon('fin') !!}
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="travailleursTableBody">
                @forelse($data_journalier ?? [] as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ $listedata->photo ? asset('rhassets/images/travailleurs/' . $listedata->photo) : asset('rhassets/images/travailleurs/default.png') }}"
                                 height="50" width="50"
                                 class="rounded-full object-cover w-12 h-12"
                                 alt="Photo {{ $listedata->nom }}">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etapedeuxtravailleur', $listedata->id ?? '') }}"
                               title="@if($listedata->numero_securite ?? false)DECLARE A LA CPNS@else MODIFIER @endif"
                               :class="$listedata->numero_securite ? 'text-red-600' : 'text-slate-900'"
                               class="font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary">
                            {{ $listedata->nom ?? '' }} {{ $listedata->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm font-semibold">
                            {{ $equipesById->get($listedata->equipeid)?->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(($listedata->etapeid ?? 0) == 2 || ($listedata->etapeid ?? 0) == 5)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">ACTIF</span>
                            @elseif(($listedata->etapeid ?? 0) == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">CESSATION</span>
                            @elseif(($listedata->etapeid ?? 0) == 4)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">CERTIFICAT</span>
                            @elseif(($listedata->etapeid ?? 0) == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">RECONDUIRE</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Declaration/Cessation Button --}}
                                @if(($listedata->etapeid ?? 0) != 3)
                                    <a id="declaration_new" data-id="{{ $listedata->id ?? '' }}"
                                       href="{{ url('action/declaration/travailleurs') }}"
                                       onclick="event.preventDefault(); openDeclarationModal({{ $listedata->id ?? 'null' }})"
                                       title="CESSATION/CERTIFICAT/DECLARATION"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa fa-map-marker"></i>
                                    </a>
                                @endif

                                {{-- Reconduire Button --}}
                                @if(($listedata->etapeid ?? 0) == 3)
                                    <a id="reconduire_journalier" data-id="{{ $listedata->id ?? '' }}"
                                       href="{{ url('reconduire/journalier') }}"
                                       onclick="event.preventDefault(); openReconduireModal({{ $listedata->id ?? 'null' }})"
                                       title="RECONDUIRE LE TRAVAILLEUR"
                                       class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                @endif

                                {{-- Contract Download Buttons --}}
                                @if(in_array($listedata->etapeid ?? 0, [2, 5, 6]))
                                    @if(($listedata->etapeid ?? 0) == 5)
                                        <a target="_blank"
                                           href="{{ route('telechargerContratDeclarationCnps',['id'=>$listedata->id ?? '', 'download'=>'pdf']) }}"
                                           title="TÉLÉCHARGER FICHE CNPS"
                                           class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @endif

                                    @if(($listedata->idtype_contrat ?? 0) == 2)
                                        <a target="_blank"
                                           href="{{ route('telechargerContratCDD',['id'=>$listedata->id ?? '', 'download'=>'pdf']) }}"
                                           title="TÉLÉCHARGER CONTRAT CDD"
                                           class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @elseif(($listedata->idtype_contrat ?? 0) == 3)
                                        <a target="_blank"
                                           href="{{ route('telechargerContratCDI',['id'=>$listedata->id ?? '', 'download'=>'pdf']) }}"
                                           title="TÉLÉCHARGER CONTRAT CDI"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @else
                                        <a target="_blank"
                                           href="{{ route('telechargerContratJournalier',['id'=>$listedata->id ?? '', 'download'=>'pdf']) }}"
                                           title="TÉLÉCHARGER CONTRAT JOURNALIER"
                                           class="p-2 text-lime-600 hover:bg-lime-50 rounded-lg transition">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @endif
                                @endif

                                {{-- Delete/Deactivate Button --}}
                                <a title="DÉSACTIVER/RETIRER"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"
                                   href="#"
                                   data-id="{{ $listedata->id ?? '' }}"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">
                            Aucun travailleur embauché trouvé
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $data_journalier->links() }}
    </div>

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')

@endsection
