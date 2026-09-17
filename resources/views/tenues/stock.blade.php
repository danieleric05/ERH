@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- Header with Breadcrumb --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Stock des tenues</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Tenues</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-primary font-semibold">Stock</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Messages --}}
        @include('success')
        @include('errors')

        {{-- Action Buttons --}}
        <div class="mb-6 flex gap-3 justify-end">
            <a href="{{ url('approvisionner-stock') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fa fa-plus mr-2"></i> Ajouter du stock
            </a>
            <a href="{{ url('liste-tenues') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition font-medium">
                <i class="fa fa-arrow-left mr-2"></i> Retour aux tenues
            </a>
        </div>

        {{-- Stock Table --}}
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('typedetenue')">
                            <div class="flex items-center gap-2">
                                Type de tenue
                                <span x-show="sortBy === 'typedetenue'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('taille')">
                            <div class="flex items-center gap-2">
                                Taille
                                <span x-show="sortBy === 'taille'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('couleur')">
                            <div class="flex items-center gap-2">
                                Couleur
                                <span x-show="sortBy === 'couleur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('quantit')">
                            <div class="flex items-center gap-2">
                                Quantité
                                <span x-show="sortBy === 'quantit'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('prixunitaire')">
                            <div class="flex items-center gap-2">
                                Prix unitaire
                                <span x-show="sortBy === 'prixunitaire'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('valeurtotale')">
                            <div class="flex items-center gap-2">
                                Valeur totale
                                <span x-show="sortBy === 'valeurtotale'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('tat')">
                            <div class="flex items-center gap-2">
                                État
                                <span x-show="sortBy === 'tat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary font-medium">Combinaison</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">M</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">Bleu</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary text-center font-semibold">45</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">2 500,00 CFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary text-center font-semibold">112 500,00 CFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">En stock</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="#" title="Modifier" class="text-slate-700 hover:text-slate-900 p-2 rounded-full hover:bg-slate-100 transition-colors">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" title="Supprimer" class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-slate-100 transition-colors" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stock Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Valeur totale du stock</p>
                        <p class="text-2xl font-bold text-text-primary">130 500,00 CFA</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa fa-money text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Articles en stock</p>
                        <p class="text-2xl font-bold text-text-primary">2</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa fa-cube text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Quantité totale</p>
                        <p class="text-2xl font-bold text-text-primary">57</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa fa-cubes text-orange-600 text-lg"></i>
                    </div>
                </div>
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
