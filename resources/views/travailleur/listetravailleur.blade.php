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

    <!-- Search Form -->
    <div class="mb-6">
        <form id="searchForm" class="flex items-center max-w-lg">
            <input type="text"
                   name="search"
                   id="searchInput"
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
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Identifiant</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom & Prénoms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date d'embauche</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date fin de contrat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Etat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody id="travailleursTableBody">
                @forelse($data_travailleurdeux ?? [] as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ asset('rhassets/images/images.png') }}"
                                 height="50" width="50"
                                 class="rounded-full object-cover w-12 h-12">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                               title="MODIFIER"
                               :class="$listedata->numero_securite ? 'text-red-600' : 'text-slate-900'"
                               class="font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-slate-900 font-bold">
                            {{ $listedata->identifiant ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-primary"
                            :title="$listedata->numero_securite ? 'DECL. CNPS' : ''">
                            <div>{{ $listedata->nom ?? '' }}</div>
                            <div>{{ $listedata->prenom ?? '' }} {{ $listedata->prenom_suite ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(($listedata->etapeid ?? null) == 2 || ($listedata->etapeid ?? null) == 5)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">ACTIF</span>
                            @elseif($listedata->etapeid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">CESSATION</span>
                            @elseif($listedata->etapeid == 4)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">CERTIFICAT</span>
                            @elseif($listedata->etapeid == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">RECONDUIRE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-wrap justify-center gap-1">
                                @if(($listedata->etapeid ?? null) != 3)
                                    <a href="{{ url('action/declaration/travailleurs') }}"
                                       title="CESSATION/CERTIFICAT/DECLARATION"
                                       data-id="{{ $listedata->id }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       >
                                        <i class="fa fa-map-marker"></i>
                                    </a>
                                @endif

                                @if($listedata->etapeid == 3)
                                    <a href="{{ url('reconduire/journalier') }}"
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

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')

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
                    // Ignorer la ligne "Aucune donnée disponible"
                    if (row.cells.length === 1 && row.cells[0].colSpan === 8) {
                        row.style.display = 'none';
                        return;
                    }

                    // Récupérer le texte des cellules importantes
                    const matricule = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
                    const identifiant = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';
                    const nomPrenom = row.cells[3] ? row.cells[3].textContent.toLowerCase() : '';

                    // Vérifier si le terme de recherche est présent
                    if (searchValue === '' ||
                        matricule.includes(searchValue) ||
                        identifiant.includes(searchValue) ||
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
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
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

@endsection