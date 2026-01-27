@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des précarités</h1>
            <a href="{{ route('listeprecarites') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Configuration</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter HA01</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Importer des données HA01</h2>

        <form action="{{ url('post_precarite_ho') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-text-primary mb-2">Type</label>
                    <select required name="type" id="type" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-DEROULER-</option>
                        <option value="J">Journalier</option>
                        <option value="E">Embauché</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div>
                    <label for="fichiers" class="block text-sm font-medium text-text-primary mb-2">Télécharger le fichier</label>
                    <input required type="file" name="fichiers" id="fichiers" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Divider -->
        <div class="border-t border-slate-200 my-8"></div>

        <!-- Export Button -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('exels_precarites_debut') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="icon-bag" aria-hidden="true"></i> EXPORTER HA01
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-center text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Élément</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Code</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Variable</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Jours travaillés</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Valeur HA01</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data_hao1 as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->matricule }}</td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->element }}</td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->code }}</td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->variable }}</td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary"><?= $val = $listedata->valeur_ha01 - $listedata->variable ?></td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->valeur_ha01 }}</td>
                        <td class="px-6 py-4 text-center text-sm text-text-primary">{{ $listedata->dateha }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer Actions -->
        @if($verif_traitement_ha01 > 0)
        <div class="p-6 border-t border-slate-200">
            <form action="{{ url('post_precarite_finalite') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition" title="FINALISER HA01">
                    <i class="icon-calculator" aria-hidden="true"></i> FINALISER
                </button>
            </form>
        </div>
        @endif
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
