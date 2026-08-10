@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des accidents de travail</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Santé</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Accidents de Travail</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('travailleur')">
                            <div class="flex items-center gap-2">
                                Travailleur
                                <span x-show="sortBy === 'travailleur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('cause')">
                            <div class="flex items-center gap-2">
                                Cause
                                <span x-show="sortBy === 'cause'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('prescription')">
                            <div class="flex items-center gap-2">
                                Prescription
                                <span x-show="sortBy === 'prescription'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Arrêt travail</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('date')">
                            <div class="flex items-center gap-2">
                                Date
                                <span x-show="sortBy === 'date'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('etat')">
                            <div class="flex items-center justify-center gap-2">
                                État
                                <span x-show="sortBy === 'etat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($listeAT ?? [] as $listedata)
                    @php
                        $travailleur = $travailleursById->get($listedata->travailleurid);
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary text-sm">
                            {{ ($travailleur->nom ?? '') }} {{ ($travailleur->prenom ?? '') }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->cause ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->prescription ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex flex-col gap-2">
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                    Début: {{ $listedata->debut_arret ?? '-' }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                    Fin: {{ $listedata->fin_arret ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->datepub ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($listedata->statutid == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">TRAITÉ</span>
                            @elseif($listedata->statutid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">EN ATTENTE</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if((Auth::user()->idrole == 1 || Auth::user()->idrole == 2) && $listedata->statutid == 1)
                                    <a href="{{ route('accident_travail_traiter', $listedata->id) }}"
                                       title="Marquer comme traité"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @elseif($listedata->statutid == 2)
                                    <span class="p-2 text-slate-400 cursor-not-allowed" title="Déjà traité">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">
                            Aucun accident de travail disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('sante.accident_travail.modal_edit')

@endsection

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
                    case 'travailleur':
                        valueA = a.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        break;
                    case 'cause':
                        valueA = a.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        break;
                    case 'prescription':
                        valueA = a.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        break;
                    case 'date':
                        valueA = a.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                    case 'etat':
                        valueA = a.querySelector('td:nth-child(6)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(6)')?.textContent.trim() || '';
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