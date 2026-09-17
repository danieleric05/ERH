@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des sanctions</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Sanctions</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-sanction') }}"
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
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('demandeur'">
                            <div class="flex items-center gap-2">
                                Demandeur
                                <span x-show="sortBy === 'demandeur'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('fautif')">
                            <div class="flex items-center justify-center gap-2">
                                Fautif(s)
                                <span x-show="sortBy === 'fautif'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('motif')">
                            <div class="flex items-center gap-2">
                                Motif
                                <span x-show="sortBy === 'motif'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('sanction')">
                            <div class="flex items-center gap-2">
                                Sanction appliquée
                                <span x-show="sortBy === 'sanction'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('resultat')">
                            <div class="flex items-center gap-2">
                                Résultat
                                <span x-show="sortBy === 'resultat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('statut')">
                            <div class="flex items-center justify-center gap-2">
                                États
                                <span x-show="sortBy === 'statut'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('date')">
                            <div class="flex items-center gap-2">
                                Date sanction
                                <span x-show="sortBy === 'date'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($Sanctions ?? [] as $vari)
                    @php
                        $demandeur = \App\Travailleur::where('id', $vari->demandeurid)->first();
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary text-sm">
                            @if($demandeur)
                                <div class="font-semibold">{{ $demandeur->nom ?? '-' }}</div>
                                <div class="text-slate-500 text-xs">{{ $demandeur->prenom ?? '-' }}</div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex flex-wrap gap-1 justify-center">
                                @php
                                    $matricules = [];
                                    if (!empty($vari->employeid)) {
                                        try {
                                            $matricules = unserialize($vari->employeid);
                                            if (!is_array($matricules)) {
                                                $matricules = [$matricules];
                                            }
                                        } catch (Exception $e) {
                                            $matricules = [];
                                        }
                                    }
                                @endphp
                                @forelse($matricules as $matricule)
                                    @php
                                        $travailleur = \App\Travailleur::where('matricule', $matricule)->first();
                                    @endphp
                                    @if($travailleur)
                                        <span title="{{ $travailleur->nom }} {{ $travailleur->prenom }}" class="px-2 py-1 text-xs font-semibold text-slate-800 bg-slate-100 rounded-full">
                                            {{ $matricule }}
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-slate-400 text-xs">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-text-secondary">
                            @switch($vari->motif)
                                @case(1)
                                    <span class="font-medium">Absence injustifiée</span>
                                    @break
                                @case(2)
                                    <span class="font-medium">Insubordination</span>
                                    @break
                                @case(3)
                                    <span class="font-medium">Retard répétitif</span>
                                    @break
                                @case(4)
                                    <span class="font-medium">Faute lourde</span>
                                    @break
                                @case(5)
                                    <span class="font-medium">Insuffisance de rendement</span>
                                    @break
                                @case(6)
                                    <span class="font-medium">Négligence professionnelle</span>
                                    @break
                                @default
                                    <span class="text-slate-400">-</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-text-primary" title="{{ $vari->expose_motif ?? '' }}">
                            @switch($vari->sanction_applique)
                                @case(1)
                                    <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded">Avertissement</span>
                                    @break
                                @case(2)
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">Mise à pied</span>
                                    @break
                                @case(3)
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">Licenciement</span>
                                    @break
                                @default
                                    <span class="text-slate-400">-</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-text-primary">
                            @switch($vari->sanction_applique)
                                @case(1)
                                    <span class="font-medium">Avertissement</span>
                                    @break
                                @case(2)
                                    <span class="font-medium">{{ $vari->nombre_jour ?? 0 }} jour(s)</span>
                                    @break
                                @case(3)
                                    <span class="font-medium">Licenciement</span>
                                    @break
                                @default
                                    <span class="text-slate-400">-</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex flex-col gap-1 items-center">
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">C.S</span>
                                <span class="px-2 py-1 text-xs font-semibold text-slate-800 bg-slate-100 rounded">D.U</span>
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">DRH</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-text-secondary">
                            {{ $vari->datesanction ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Download Button --}}
                                <a title="Télécharger" href="{{ route('techarger_sanctions', $vari->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-download"></i>
                                </a>

                                {{-- Add to Variables Button --}}
                                @if($vari->statutid == 1)
                                    <a title="Ajouter aux variables" href="{{ route('sanctionvariable', $vari->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                @else
                                    <span title="Sanction déjà ajoutée aux variables" class="p-2 text-slate-400 cursor-not-allowed">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-text-secondary">
                            Aucune sanction disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

<script>
function tableSort() {
    return {
        sortBy: 'date',
        sortDir: 'desc',

        sort(column) {
            // Si on clique sur la même colonne, inverser la direction
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc'; // Par défaut croissant pour une nouvelle colonne
            }

            this.sortTable();
        },

        sortTable() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:last-child)'));

            rows.sort((a, b) => {
                let valueA, valueB;

                switch(this.sortBy) {
                    case 'demandeur':
                        valueA = a.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(1)')?.textContent.trim() || '';
                        break;
                    case 'fautif':
                        valueA = a.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(2)')?.textContent.trim() || '';
                        break;
                    case 'motif':
                        valueA = a.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(3)')?.textContent.trim() || '';
                        break;
                    case 'sanction':
                        valueA = a.querySelector('td:nth-child(4) span')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(4) span')?.textContent.trim() || '';
                        break;
                    case 'resultat':
                        valueA = a.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(5)')?.textContent.trim() || '';
                        break;
                    case 'statut':
                        valueA = a.querySelector('td:nth-child(6)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(6)')?.textContent.trim() || '';
                        break;
                    case 'date':
                        valueA = a.querySelector('td:nth-child(7)')?.textContent.trim() || '';
                        valueB = b.querySelector('td:nth-child(7)')?.textContent.trim() || '';
                        // Convertir au format comparable pour les dates
                        valueA = new Date(valueA).getTime() || 0;
                        valueB = new Date(valueB).getTime() || 0;
                        break;
                }

                // Comparaison numérique ou textuelle
                if (typeof valueA === 'number' && typeof valueB === 'number') {
                    return this.sortDir === 'asc' ? valueA - valueB : valueB - valueA;
                } else {
                    return this.sortDir === 'asc'
                        ? String(valueA).localeCompare(String(valueB), 'fr-FR')
                        : String(valueB).localeCompare(String(valueA), 'fr-FR');
                }
            });

            // Réinsérer les lignes triées
            rows.forEach(row => tbody.appendChild(row));
        },

        init() {
            // Trier par défaut par date décroissante au chargement
            this.$nextTick(() => {
                this.sortTable();
            });
        }
    }
}
</script>