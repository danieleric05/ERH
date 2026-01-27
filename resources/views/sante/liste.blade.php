@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des consultations</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Consultations Santé</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste</span>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('infirmier')">
                            <div class="flex items-center gap-2">
                                Infirmier
                                <span x-show="sortBy === 'infirmier'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('matricule')">
                            <div class="flex items-center gap-2">
                                Matricule
                                <span x-show="sortBy === 'matricule'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('travailleur')">
                            <div class="flex items-center gap-2">
                                Travailleur
                                <span x-show="sortBy === 'travailleur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        @if(Auth::user()->idrole == 4)
                            <th class="px-6 py-4 text-left text-sm font-semibold">Consultation</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Prescription</th>
                        @endif
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('arret')">
                            <div class="flex items-center justify-center gap-2">
                                Arrêt travail
                                <span x-show="sortBy === 'arret'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('debut')">
                            <div class="flex items-center justify-center gap-2">
                                Début
                                <span x-show="sortBy === 'debut'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('fin')">
                            <div class="flex items-center justify-center gap-2">
                                Fin
                                <span x-show="sortBy === 'fin'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('etat')">
                            <div class="flex items-center justify-center gap-2">
                                État
                                <span x-show="sortBy === 'etat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('enregistre')">
                            <div class="flex items-center gap-2">
                                Enregistré le
                                <span x-show="sortBy === 'enregistre'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                    <tbody>
                        @forelse($listeSante ?? [] as $listedata)
                            @php
                                $infirmier = \App\User::where('id', $listedata->userid)->first();
                                $travailleur = \App\Travailleur::where('id', $listedata->travailleurid)->first();
                                $receptionnaire = $listedata->recu_par ? \App\User::where('id', $listedata->recu_par)->first() : null;
                            @endphp
                            <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                    {{ $infirmier->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $travailleur->matricule ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                    {{ ($travailleur->nom ?? '') }} {{ ($travailleur->prenom ?? '') }}
                                </td>
                                @if(Auth::user()->idrole == 4)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                        {{ $listedata->consultation ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                        {{ $listedata->prescription ?? '-' }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($listedata->arret_travail == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">AVEC ARRÊT</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">SANS ARRÊT</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $listedata->debut_arret ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $listedata->fin_arret ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($listedata->statutid == 1 && $listedata->arret_travail == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">Non reçu DRH</span>
                                    @elseif($listedata->statutid == 2 && $listedata->arret_travail == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded">
                                            Reçu par {{ $receptionnaire->name ?? '-' }}
                                        </span>
                                    @elseif($listedata->statutid == 3 && $listedata->arret_travail == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded">
                                            Reçu + Variables
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $listedata->created_at ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        @if(Auth::user()->idrole == 1 || Auth::user()->idrole == 2)
                                            {{-- Mark as Received --}}
                                            <a title="Arrêt travail reçu"
                                               href="{{ route('patientrexu', $listedata->id) }}"
                                               class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                                <i class="fa fa-check"></i>
                                            </a>

                                            {{-- Add to Variables --}}
                                            @if($listedata->statutid != 3)
                                                <a title="Ajouter aux variables"
                                                   href="{{ route('variables_sante', $listedata->id) }}"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                                    <i class="fa fa-thumbs-up"></i>
                                                </a>
                                            @else
                                                <span title="Ajouté aux variables"
                                                      class="p-2 text-slate-400 cursor-not-allowed">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            @endif
                                        @endif

                                        @if(Auth::user()->idrole == 4)
                                            {{-- Medical Info Button --}}
                                            <a title="Informations"
                                               data-id="{{ $listedata->id }}"
                                               id="detail"
                                               class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition cursor-pointer">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@if(Auth::user()->idrole == 4) 12 @else 10 @endif" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucune consultation trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('sante.modal_edit')

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
                const baseCol = this.getBaseColumnIndex();

                switch(this.sortBy) {
                    case 'infirmier':
                        valueA = a.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        break;
                    case 'matricule':
                        valueA = a.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        break;
                    case 'travailleur':
                        valueA = a.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        break;
                    case 'arret':
                        valueA = a.querySelector('td:nth-child(' + (baseCol + 1) + ')').textContent.includes('AVEC') ? 1 : 0;
                        valueB = b.querySelector('td:nth-child(' + (baseCol + 1) + ')').textContent.includes('AVEC') ? 1 : 0;
                        break;
                    case 'debut':
                        valueA = a.querySelector('td:nth-child(' + (baseCol + 2) + ')')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(' + (baseCol + 2) + ')')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                    case 'fin':
                        valueA = a.querySelector('td:nth-child(' + (baseCol + 3) + ')')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(' + (baseCol + 3) + ')')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                    case 'etat':
                        valueA = a.querySelector('td:nth-child(' + (baseCol + 4) + ')')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(' + (baseCol + 4) + ')')?.textContent.trim() || '';
                        break;
                    case 'enregistre':
                        valueA = a.querySelector('td:nth-child(' + (baseCol + 5) + ')')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(' + (baseCol + 5) + ')')?.textContent.trim() || '';
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
        },

        getBaseColumnIndex() {
            // Déterminer le nombre de colonnes avant les colonnes de base
            const headerCells = document.querySelectorAll('thead th');
            let count = 0;
            headerCells.forEach((cell, idx) => {
                if (idx < 3) return; // Infirmier, Matricule, Travailleur
                if (cell.textContent.includes('Consultation') || cell.textContent.includes('Prescription')) {
                    count++;
                } else {
                    return;
                }
            });
            return 3 + count;
        }
    }
}
</script>