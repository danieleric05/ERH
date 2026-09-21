@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des tenues</h1>
            <a href="{{ route('listetenues') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <a href="{{ route('listetenues') }}" class="hover:text-primary-accent">Tenues</a>
            <span>/</span>
            <span class="text-text-primary font-medium">Approvisionnement du stock</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8" x-data="tableSort()">
        <h2 class="text-xl font-bold text-text-primary mb-6">Ajouter un approvisionnement</h2>

        <form action="{{ url('post_appro_stock') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Date -->
                <div>
                    <label for="date_reception" class="block text-sm font-medium text-text-primary mb-2">Date</label>
                    <input name="date_reception" id="date_reception" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Article -->
                <div>
                    <label for="tenuerecu" class="block text-sm font-medium text-text-primary mb-2">Article</label>
                    <select name="tenuerecu" id="tenuerecu" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_ArticleRecu as $data)
                             <option value="{{ $data->id }}">{{ $data->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantité -->
                <div>
                    <label for="quantite" class="block text-sm font-medium text-text-primary mb-2">Quantité</label>
                    <input name="quantite" id="quantite" type="number" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Fournisseur -->
                <div>
                    <label for="fournisseur" class="block text-sm font-medium text-text-primary mb-2">Fournisseur</label>
                    <input name="fournisseur" id="fournisseur" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Enregistrer
                </button>
                <a href="{{ route('listetenues') }}" class="px-8 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Liste
                </a>
            </div>
        </form>
    </div>

    <!-- Divider -->
    <div class="border-t-2 border-slate-300 my-8"></div>

    <!-- Approvisionnement List -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-bold text-text-primary">Historique des approvisionnements</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('article')">
                            <div class="flex items-center gap-2">
                                Article
                                <span x-show="sortBy === 'article'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('quantit')">
                            <div class="flex items-center gap-2">
                                Quantité
                                <span x-show="sortBy === 'quantit'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('fournisseur')">
                            <div class="flex items-center gap-2">
                                Fournisseur
                                <span x-show="sortBy === 'fournisseur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('datederception')">
                            <div class="flex items-center gap-2">
                                Date de réception
                                <span x-show="sortBy === 'datederception'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_ApproTenues ?? [] as $tenues)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-text-primary">
                                {{ optional(App\ArticleRecu::where('id', $tenues->articleid)->first())->label ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $tenues->quantite }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $tenues->fournisseur }}</td>
                            <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $tenues->date_recep }}</td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a title="Modifier" href="#" class="inline-flex items-center gap-2 px-3 py-1 text-slate-700 hover:text-slate-900 transition">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-text-secondary">Aucun approvisionnement enregistré</td>
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
