@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestions des variables (heure supplémentaire)</h1>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Configuration</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Liste des variables (heure supplémentaire)</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('demandeur')">
                            <div class="flex items-center gap-2">
                                Demandeur
                                <span x-show="sortBy === 'demandeur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('dbut')">
                            <div class="flex items-center gap-2">
                                Début
                                <span x-show="sortBy === 'dbut'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('fin')">
                            <div class="flex items-center gap-2">
                                Fin
                                <span x-show="sortBy === 'fin'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nombredheure')">
                            <div class="flex items-center gap-2">
                                Nombre d'heure
                                <span x-show="sortBy === 'nombredheure'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('justification')">
                            <div class="flex items-center gap-2">
                                Justification
                                <span x-show="sortBy === 'justification'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('tat')">
                            <div class="flex items-center gap-2">
                                État
                                <span x-show="sortBy === 'tat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_variables_heure_supp ?? [] as $item)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->demandeur ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->debut ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->fin ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->nombre_heure ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->justification ?? '-' }}</td>
                            <td class="px-6 py-4 text-center text-sm">
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">Actif</span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a title="Modifier" href="#" class="inline-flex items-center gap-2 px-3 py-1 text-slate-700 hover:text-slate-900 transition">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a title="Supprimer" href="#" class="inline-flex items-center gap-2 px-3 py-1 text-red-600 hover:text-red-800 transition">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-text-secondary">Aucune donnée disponible</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('tenues.modal_edit')


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

                // Récupérer les données de la colonne
                const cells = Array.from(a.querySelectorAll('td'));
                if (cells.length === 0) return 0;

                // Déterminer l'index de la colonne
                let colIndex = 0;
                const headers = document.querySelectorAll('thead th');
                let clickCount = 0;
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].textContent.toLowerCase().includes(this.sortBy.toLowerCase())) {
                        colIndex = i;
                        break;
                    }
                }

                valueA = a.querySelector('td:nth-child(' + (colIndex + 1) + ')')?.textContent.trim() || '';
                valueB = b.querySelector('td:nth-child(' + (colIndex + 1) + ')')?.textContent.trim() || '';

                // Essayer de convertir en date
                const dateA = new Date(valueA).getTime();
                const dateB = new Date(valueB).getTime();

                if (!isNaN(dateA) && !isNaN(dateB) && dateA > 0 && dateB > 0) {
                    return this.sortDir === 'asc' ? dateA - dateB : dateB - dateA;
                }

                // Comparaison textuelle
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
