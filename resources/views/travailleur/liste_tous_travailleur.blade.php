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

        {{-- Search Form --}}
        <div class="mb-6 flex justify-end">
            <form action="{{ route('liste_travailleurs') }}" method="GET" class="flex items-center max-w-lg" id="searchForm">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom, matricule..." class="w-full px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-primary-accent focus:border-primary-accent transition-shadow" autocomplete="off">
                <button type="submit" class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-r-lg hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition-colors">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        {{-- Tableau --}}
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200" id="travailleursTable">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition select-none" @click="sort('matricule')">
                                <div class="flex items-center gap-2">
                                    Matricule
                                    <span x-show="sortBy === 'matricule'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition select-none" @click="sort('nom')">
                                <div class="flex items-center gap-2">
                                    Nom & Prénoms
                                    <span x-show="sortBy === 'nom'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition select-none" @click="sort('embauche')">
                                <div class="flex items-center gap-2">
                                    Embauche
                                    <span x-show="sortBy === 'embauche'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition select-none" @click="sort('fin')">
                                <div class="flex items-center gap-2">
                                    Fin contrat
                                    <span x-show="sortBy === 'fin'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                                </div>
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
    </div>

    {{-- Script de recherche en temps réel --}}
    <script>
        (function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('travailleursTableBody');
            const searchForm = document.getElementById('searchForm');
            const allRows = Array.from(tableBody.querySelectorAll('tr'));

            // Fonction de recherche côté client
            function filterTable() {
                const searchValue = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                allRows.forEach(function(row) {
                    // Ignorer la ligne "Aucun travailleur trouvé"
                    if (row.cells.length === 1 && row.cells[0].colSpan === 7) {
                        row.style.display = 'none';
                        return;
                    }

                    // Récupérer le texte de toutes les cellules
                    const matricule = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
                    const nomPrenom = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';

                    // Vérifier si le terme de recherche est présent
                    if (searchValue === '' ||
                        matricule.includes(searchValue) ||
                        nomPrenom.includes(searchValue)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Afficher un message si aucun résultat
                if (visibleCount === 0 && searchValue !== '') {
                    const noResultRow = tableBody.querySelector('.no-result-row');
                    if (!noResultRow) {
                        const tr = document.createElement('tr');
                        tr.className = 'no-result-row';
                        tr.innerHTML = `
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                <p class="text-lg">Aucun travailleur trouvé pour "${searchValue}"</p>
                            </td>
                        `;
                        tableBody.appendChild(tr);
                    }
                } else {
                    // Supprimer le message "aucun résultat" s'il existe
                    const noResultRow = tableBody.querySelector('.no-result-row');
                    if (noResultRow) {
                        noResultRow.remove();
                    }
                }
            }

            // Écouter l'événement input pour la recherche instantanée
            searchInput.addEventListener('input', filterTable);

            // Empêcher la soumission normale du formulaire
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                filterTable();
            });

            // Filtrer au chargement si une valeur est présente
            if (searchInput.value.trim() !== '') {
                filterTable();
            }
        })();
    </script>

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
            const rows = Array.from(tbody.querySelectorAll('tr:not(:last-child)'));

            rows.sort((a, b) => {
                let valueA, valueB;

                switch(this.sortBy) {
                    case 'matricule':
                        valueA = a.querySelector('td:nth-child(2) a')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(2) a')?.textContent.trim() || '';
                        break;
                    case 'nom':
                        valueA = a.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        break;
                    case 'embauche':
                        valueA = a.querySelector('td:nth-child(4)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(4)')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                    case 'fin':
                        valueA = a.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                }

                if (typeof valueA === 'number' && typeof valueB === 'number') {
                    return this.sortDir === 'asc' ? valueA - valueB : valueB - valueA;
                } else {
                    return this.sortDir === 'asc'
                        ? String(valueA).localeCompare(String(valueB), 'fr-FR')
                        : String(valueB).localeCompare(String(valueA), 'fr-FR');
                }
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    }
}
</script>

@endsection
