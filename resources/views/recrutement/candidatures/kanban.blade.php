@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Candidatures — Vue Kanban</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ route('dashboard') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Candidatures — Kanban</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('candidatures.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                    <i class="fa fa-list"></i> Vue liste
                </a>
            </div>
        </div>
    </div>

    @include('success')
    @include('errors')

    <!-- Filtre par offre -->
    <div class="bg-white rounded-lg shadow-lg-soft p-4 mb-6">
        <form method="GET" action="{{ route('candidatures.kanban') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Offre d'emploi</label>
                <select name="offre" onchange="this.form.submit()" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                    <option value="">Toutes les offres publiées</option>
                    @foreach($offres as $offre)
                        <option value="{{ $offre->id }}" @selected(request('offre') == $offre->id)>{{ $offre->titre }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Colonnes Kanban -->
    <div class="flex gap-4 overflow-x-auto pb-4">
        @foreach($statuts as $statut)
            @php $colCandidatures = $candidatures->get($statut->id, collect()); @endphp
            <div class="flex-shrink-0 w-72 bg-slate-50 rounded-lg">
                <div class="px-4 py-3 rounded-t-lg text-white font-semibold flex items-center justify-between"
                     style="background-color: {{ $statut->couleur }};">
                    <span>{{ $statut->libelle }}</span>
                    <span class="bg-white/30 text-white text-xs px-2 py-0.5 rounded-full">{{ $colCandidatures->count() }}</span>
                </div>
                <div class="p-3 space-y-3 min-h-[100px]">
                    @forelse($colCandidatures as $candidature)
                        <a href="{{ route('candidatures.show', $candidature->id) }}"
                           class="block bg-white rounded-lg shadow-sm p-3 hover:shadow-md transition">
                            <p class="font-semibold text-text-primary text-sm">
                                {{ $candidature->candidat->nom ?? '' }} {{ $candidature->candidat->prenom ?? '' }}
                            </p>
                            <p class="text-text-secondary text-xs mt-1">{{ $candidature->offre->titre ?? '-' }}</p>
                        </a>
                    @empty
                        <p class="text-text-secondary text-xs text-center py-4">Aucune candidature</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

@endsection
