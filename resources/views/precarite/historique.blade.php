@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Historiques</h1>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>HA01</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Historiques des HA01</span>
        </nav>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8">
        <h2 class="text-lg font-bold text-text-primary mb-6">Paramètres de recherche</h2>

        <form action="{{ url('post_search_histo_precarite') }}" method="POST" class="form-auth-small">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Type -->
                <div>
                    <label for="variables_type" class="block text-sm font-medium text-text-primary mb-2">Type</label>
                    <select required name="variables_type" id="variables_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white select2-active">
                        <option value="">-DEROULER-</option>
                        <option selected value="1">JOURNALIER</option>
                        <option value="2">EMBAUCHE</option>
                    </select>
                </div>

                <!-- Date début -->
                <div>
                    <label for="beginn" class="block text-sm font-medium text-text-primary mb-2">Début</label>
                    <input required type="date" id="beginn" value="<?= date('Y-m-d')?>" name="beginn" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Date fin -->
                <div>
                    <label for="endd" class="block text-sm font-medium text-text-primary mb-2">Fin</label>
                    <input required type="date" id="endd" value="<?= date('Y-m-d')?>" max="<?= date('Y-m-d') ?>" name="endd" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="px-12 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('n')">
                            <div class="flex items-center gap-2">
                                N°
                                <span x-show="sortBy === 'n'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('matricule')">
                            <div class="flex items-center gap-2">
                                Matricule
                                <span x-show="sortBy === 'matricule'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nomprnoms')">
                            <div class="flex items-center gap-2">
                                Nom & Prénoms
                                <span x-show="sortBy === 'nomprnoms'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nombredejour')">
                            <div class="flex items-center gap-2">
                                Nombre de jour
                                <span x-show="sortBy === 'nombredejour'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('tat')">
                            <div class="flex items-center gap-2">
                                État
                                <span x-show="sortBy === 'tat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @if(!($recherches) && !($variables))
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-text-secondary">AUCUNE DONNÉE DISPONIBLE</td>
                    </tr>
                @endif

                @if($recherches)
                    <?php $i=1; ?>
                    @foreach($recherches as $rech)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-text-primary"><?= $i++ ?></td>
                            <td class="px-6 py-4 text-sm text-text-primary"><?= $rech->matricule ?></td>
                            @if($rech->matricule)
                                <td class="px-6 py-4 text-sm text-text-primary">
                                    {{ optional($travailleursByMatricule->get($rech->matricule))->nom }}
                                    {{ optional($travailleursByMatricule->get($rech->matricule))->prenom }}
                                </td>
                            @endif
                            <td class="px-6 py-4 text-center text-sm text-text-primary"><?= $rech->valeur ?></td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($rech->statutid == 2)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">Non Payé</span>
                                @endif
                                @if($rech->statutid == 3)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">Payé</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <!-- Summary Rows -->
                    <tr class="bg-slate-100 border-t-2 border-slate-300">
                        <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-red-600">
                            MONTANT À PAYER :
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-red-600">
                            <?php
                            $ttHa = 0;
                            $Apayer = 0;
                            foreach($recherches as $reching){
                                if($reching->statutid != 3){
                                    $ttHa += $reching->valeur;
                                    $Apayer += 2768 + (176*0.03*$ttHa);
                                }
                            }
                            echo number_format($Apayer, '2', ',', '.');
                            ?>
                        </td>
                    </tr>
                    <tr class="bg-slate-100">
                        <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-text-primary">
                            MONTANT PAYÉ :
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-text-primary">
                            <?php
                            $ttHaP = 0;
                            $Apayerp = 0;
                            foreach($recherches as $rechingp){
                                if($rechingp->statutid == 3){
                                    $ttHaP += $rechingp->valeur;
                                    $Apayerp += 2768 + (176*0.03*$ttHaP);
                                }
                            }
                            echo number_format($Apayerp, '2', ',', '.');
                            ?>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Export Button -->
        @if($recherches)
        <div class="p-6 border-t border-slate-200 flex justify-end">
            <a href="{{ route('exels_precarites', $code) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" title="EXPORTER EN EXCEL">
                <i class="icon-folder" aria-hidden="true"></i> Exporter
            </a>
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
