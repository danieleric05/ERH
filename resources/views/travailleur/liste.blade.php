@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des travailleurs à étape deux</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Travailleurs Étape Deux</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-travailleur-etape-un') }}"
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
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('matricule')">
                            <div class="flex items-center gap-2">
                                Matricule
                                <span x-show="sortBy === 'matricule'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nom')">
                            <div class="flex items-center gap-2">
                                Nom & Prénoms
                                <span x-show="sortBy === 'nom'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('equipe')">
                            <div class="flex items-center gap-2">
                                Équipe
                                <span x-show="sortBy === 'equipe'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('embauche')">
                            <div class="flex items-center gap-2">
                                Date d'embauche
                                <span x-show="sortBy === 'embauche'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('fin')">
                            <div class="flex items-center gap-2">
                                Date fin de contrat
                                <span x-show="sortBy === 'fin'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data_travailleurdeux ?? [] as $listedata)
                    @php
                        $equipe = \App\Equipes::where('id', $listedata->equipeid)->first();
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ $listedata->photo
                                    ? asset('rhassets/images/travailleurs/' . $listedata->photo)
                                    : asset('rhassets/images/travailleurs/default.png') }}"
                                 alt="Photo {{ $listedata->nom }}"
                                 height="50" width="50"
                                 class="rounded-full object-cover w-12 h-12">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                               title="MODIFIER"
                               class="text-slate-900 font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary">
                            {{ $listedata->nom ?? '' }} {{ $listedata->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm font-semibold">
                            {{ $equipe?->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">ÉTAPE 2</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Modifier --}}
                                <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                                   title="MODIFIER"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </a>

                                {{-- Supprimer --}}
                                <a href="javascript:void(0)"
                                   onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce travailleur?')) { window.location.href='{{ url('delete/categories/data') }}?id={{ $listedata->id }}'; }"
                                   title="SUPPRIMER"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">
                            Aucun travailleur à étape deux disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('configuration.niveauEtude.modal_niveauEtude')

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
                    case 'matricule':
                        valueA = a.querySelector('td:nth-child(2) a')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(2) a')?.textContent.trim() || '';
                        break;
                    case 'nom':
                        valueA = a.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        break;
                    case 'equipe':
                        valueA = a.querySelector('td:nth-child(4)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(4)')?.textContent.trim() || '';
                        break;
                    case 'embauche':
                        valueA = a.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                    case 'fin':
                        valueA = a.querySelector('td:nth-child(6)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(6)')?.textContent.trim() || '';
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
        }
    }
}
</script>