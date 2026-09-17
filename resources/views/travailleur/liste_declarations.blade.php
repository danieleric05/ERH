@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des travailleurs non déclarés</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Non déclarés</span>
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
                @forelse($data_declarations ?? [] as $listedata)
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
                               class="text-red-600 font-bold hover:underline">
                                {{ $listedata->matricule ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary">
                            {{ $listedata->nom ?? '' }} {{ $listedata->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $equipesById->get($listedata->equipeid)?->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">DÉCLARATION</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Modifier --}}
                                <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                                   title="MODIFIER"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </a>

                                {{-- Ajouter CNPS --}}
                                <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                                   title="AJOUTER SON NUMÉRO CNPS"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>

                                {{-- Historiques --}}
                                <a href="{{ route('historiques_contrat', $listedata->id) }}"
                                   title="HISTORIQUES"
                                   class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                                    <i class="fa fa-history"></i>
                                </a>

                                {{-- Supprimer --}}
                                <a href="javascript:void(0)"
                                   onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce travailleur?')) { window.location.href='{{ url('delete/travailleur') }}?id={{ $listedata->id }}'; }"
                                   title="SUPPRIMER"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">
                            Tous les travailleurs sont déclarés à la CNPS
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $data_declarations->links() }}
    </div>

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')

@endsection
