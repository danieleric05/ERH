@extends('layouts.erh')

@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Offres d'emploi</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ route('dashboard') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Offres d'emploi</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('offres.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fa fa-plus"></i> Nouvelle offre
                </a>
            </div>
        </div>
    </div>

    @include('success')
    @include('errors')

    <div class="mb-6 flex justify-end items-center gap-3">
        <form id="searchForm" class="flex items-center gap-3">
            <input type="text" placeholder="Rechercher..." id="searchInput"
                   class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
            <select id="filterStatus"
                    class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                <option value="">Tous les statuts</option>
                <option value="1">Publiée</option>
                <option value="2">Clôturée</option>
                <option value="3">Pourvue</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Titre</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Type contrat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Département</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fonction</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Postes</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Statut</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date clôture</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($offres as $offre)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('offres.show', $offre->id) }}" class="font-semibold text-primary-accent hover:underline">
                                {{ $offre->titre }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white
                                {{ $offre->type_contrat === 'CDI' ? 'bg-green-600' : ($offre->type_contrat === 'CDD' ? 'bg-blue-600' : 'bg-orange-500') }}">
                                {{ $offre->type_contrat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $offre->departement->libelle ?? '-' }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $offre->fonction->libelle ?? '-' }}</td>
                        <td class="px-6 py-4 text-center text-text-primary">{{ $offre->nombre_postes }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($offre->statut === 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Publiée</span>
                            @elseif($offre->statut === 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Clôturée</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Pourvue</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $offre->date_cloture ? $offre->date_cloture->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('offres.show', $offre->id) }}" title="VOIR DÉTAILS"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('offres.edit', $offre->id) }}" title="ÉDITER"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @if($offre->statut !== 3)
                                    <form action="{{ route('offres.close', $offre->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Clôturer cette offre ?')">
                                        @csrf
                                        <button type="submit" title="CLÔTURER" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">Aucune offre d'emploi trouvée</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $offres->links() }}
    </div>

@endsection
