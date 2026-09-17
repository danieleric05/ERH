@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Certificats de travail</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Certificats de travail</span>
                </nav>
            </div>
        </div>
    </div>

    @include('travailleur._tabs')

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
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
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nomprnoms')">
                            <div class="flex items-center gap-2">
                                Nom & Prénoms
                                <span x-show="sortBy === 'nomprnoms'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('quipe')">
                            <div class="flex items-center gap-2">
                                Équipe
                                <span x-show="sortBy === 'quipe'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('datedembauche')">
                            <div class="flex items-center gap-2">
                                Date d'embauche
                                <span x-show="sortBy === 'datedembauche'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('datefindecontrat')">
                            <div class="flex items-center gap-2">
                                Date fin de contrat
                                <span x-show="sortBy === 'datefindecontrat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
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
                @forelse($data_certificat_travail ?? [] as $listedata)
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
                               class="text-red-600 font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary">
                            {{ $listedata->nom ?? '' }} {{ $listedata->prenom ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            @php
                                $equipe = $equipesById->get($listedata->equipeid);
                            @endphp
                            {{ $equipe?->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($listedata->etapeid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">CESSATION</span>
                            @elseif($listedata->etapeid == 4)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">CERTIFICAT</span>
                            @elseif($listedata->etapeid == 5)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">DÉCLARATION</span>
                            @elseif($listedata->etapeid == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">RECONDUIRE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Modifier --}}
                                <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                                   title="MODIFIER"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </a>

                                {{-- Précarité --}}
                                <a href="#" title="PRÉCARITÉ"
                                   class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-briefcase"></i>
                                </a>

                                {{-- Historiques --}}
                                <a href="{{ route('historiques_contrat', $listedata->id) }}"
                                   title="HISTORIQUES"
                                   class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                                    <i class="fa fa-history"></i>
                                </a>

                                {{-- Reconduire --}}
                                @if($listedata->etapeid == 4)
                                    <a href="javascript:void(0)"
                                       onclick="openReconduite({{ $listedata->id }})"
                                       title="RECONDUIRE LE TRAVAILLEUR"
                                       class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                @endif

                                {{-- Télécharger certificat --}}
                                <a href="{{ route('telechargerContratCertificatTravail', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                   target="_blank"
                                   title="TÉLÉCHARGER LE CERTIFICAT"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>

                                {{-- Supprimer --}}
                                <a href="javascript:void(0)"
                                   onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce travailleur?')) { window.location.href='{{ url('delete/travailleur') }}?id={{ $listedata->id }}'; }"
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
                            Aucun certificat de travail disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')


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