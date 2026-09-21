@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des variables manuelles</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Variables</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Manuelles</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-variable') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fa fa-plus"></i> Ajouter
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('type')">
                            <div class="flex items-center gap-2">
                                Type
                                <span x-show="sortBy === 'type'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('employs')">
                            <div class="flex items-center gap-2">
                                Employés
                                <span x-show="sortBy === 'employs'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('cas')">
                            <div class="flex items-center gap-2">
                                Cas
                                <span x-show="sortBy === 'cas'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('priode')">
                            <div class="flex items-center gap-2">
                                Période
                                <span x-show="sortBy === 'priode'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
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
                @forelse($variableM ?? [] as $vari)
                    <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary" title="{{ $vari->justification ?? '' }}">
                                    @switch($vari->type_variable)
                                        @case(1)
                                            <span class="font-medium">Dimanche</span>
                                            @break
                                        @case(2)
                                            <span class="font-medium">Férié</span>
                                            @break
                                        @case(3)
                                            <span class="font-medium">Jour ouvrable</span>
                                            @break
                                        @default
                                            <span class="text-slate-400">-</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($vari->matricules as $matricule)
                                            @php
                                                $travailleur = $travailleursByMatricule->get($matricule);
                                            @endphp
                                            <span title="{{ $travailleur ? $travailleur->nom . ' ' . $travailleur->prenoms_complets : 'Matricule inconnu' }}" class="px-2 py-1 text-xs font-semibold {{ $travailleur ? 'text-gray-800 bg-gray-100' : 'text-amber-800 bg-amber-100' }} rounded-full">
                                                {{ $matricule }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-secondary">
                                    @switch($vari->cas_variables)
                                        @case(1)
                                            Retard d'enrôlement
                                            @break
                                        @case(2)
                                            Défaut de pointage
                                            @break
                                        @case(3)
                                            Oubli de pointage
                                            @break
                                        @case(4)
                                            Défaut d'empreinte
                                            @break
                                        @default
                                            <span class="text-slate-400">-</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    @if($vari->periode == 2)
                                        <span class="font-medium">Jour</span>
                                    @elseif($vari->periode == 1)
                                        <span class="font-medium">Nuit</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $vari->debut ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $vari->fin ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($vari->statutid == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Actif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($vari->statutid == 1)
                                        <form method="POST" action="{{ route('variables_annuler', $vari->id) }}" onsubmit="return confirm('Annuler cette variable ?')" class="inline">
                                            @csrf
                                            <button type="submit" title="Annuler" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"><i class="fa fa-ban"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucune variable manuelle trouvée</p>
                                </td>
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