@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Candidatures</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ route('dashboard') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Candidatures</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('candidatures.kanban') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                    <i class="fa fa-columns"></i> Vue Kanban
                </a>
                <a href="{{ route('offres.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fa fa-briefcase"></i> Offres d'emploi
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-lg-soft p-4 mb-6">
        <form method="GET" action="{{ route('candidatures.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Offre d'emploi</label>
                <select name="offre" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                    <option value="">Toutes les offres</option>
                    @foreach($offres as $offre)
                        <option value="{{ $offre->id }}" @selected(request('offre') == $offre->id)>{{ $offre->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Statut</label>
                <select name="statut" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                    <option value="">Tous les statuts</option>
                    @foreach($statuts as $statut)
                        <option value="{{ $statut->id }}" @selected(request('statut') == $statut->id)>{{ $statut->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                <i class="fa fa-filter"></i> Filtrer
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Candidat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Offre</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date candidature</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Statut</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($candidatures as $candidature)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary text-sm">
                            {{ $candidature->candidat->nom ?? '' }} {{ $candidature->candidat->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $candidature->offre->titre ?? '-' }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $candidature->date_candidature }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                                  style="background-color: {{ $candidature->statut->couleur ?? '#94a3b8' }}22; color: {{ $candidature->statut->couleur ?? '#64748b' }};">
                                {{ $candidature->statut->libelle ?? 'Reçu' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('candidatures.show', $candidature->id) }}" title="Voir"
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-text-secondary">
                            Aucune candidature trouvée.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $candidatures->appends(request()->query())->links() }}
        </div>
    </div>

@endsection
