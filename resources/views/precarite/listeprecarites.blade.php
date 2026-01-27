@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- En-tête avec Breadcrumb et Titre --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des précarités</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Précarités</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Liste</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Tableau --}}
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">N°</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Matricule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nom & Prénoms</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre de jours</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">État</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($Precarites ?? [] as $index => $rech)
                            @php
                                $travailleur = $rech->matricule ? \App\Travailleur::where('matricule', $rech->matricule)->first() : null;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-text-primary">
                                    {{ $rech->matricule ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                    @if($travailleur)
                                        {{ $travailleur->nom ?? '' }} {{ $travailleur->prenom ?? '' }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-text-secondary">
                                    {{ $rech->valeur ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($rech->statutid == 2)
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Non payé</span>
                                    @elseif($rech->statutid == 3)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Payé</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Edit Button --}}
                                        <a title="Modifier"
                                           href="{{ url('edit/precarite/' . ($rech->id ?? '')) }}"
                                           class="text-slate-700 hover:text-slate-900 p-2 rounded-full hover:bg-slate-100 transition-colors">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette précarité ?')"
                                           href="{{ url('delete/precarite/' . ($rech->id ?? '')) }}"
                                           class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-slate-100 transition-colors">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucune précarité trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
@endsection