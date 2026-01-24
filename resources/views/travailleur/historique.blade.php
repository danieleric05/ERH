@extends('layouts.erh')
@section('content')

<div class="px-6 py-8">
    {{-- En-tête avec breadcrumb --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Recherche & Historique</h1>
            <nav class="text-sm font-medium text-slate-500 mt-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="icon-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Recherches</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Formulaire de recherche --}}
    <div class="bg-white rounded-lg shadow-lg-soft p-6 mb-8" x-data="searchForm()">
        <h2 class="text-xl font-semibold text-text-primary mb-6">Critères de recherche</h2>

        <form action="{{ url('post_search') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Type de travailleur --}}
                <div>
                    <label for="type_id" class="block text-sm font-medium text-text-primary mb-2">
                        Type de travailleur <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="type_id"
                        id="type_id"
                        required
                        x-model="typeId"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-accent focus:border-transparent transition-all"
                    >
                        <option value="">-- Sélectionner --</option>
                        <option value="1">Embauché</option>
                        <option value="2">Journalier</option>
                    </select>
                </div>

                {{-- Recherche par --}}
                <div>
                    <label for="recherche" class="block text-sm font-medium text-text-primary mb-2">
                        Rechercher par <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="recherche"
                        id="recherche"
                        required
                        x-model="rechercheType"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-accent focus:border-transparent transition-all"
                    >
                        <option value="">-- Sélectionner --</option>
                        <option value="1">Unité</option>
                        <option value="2">Toutes les Unités</option>
                        <option value="3">Période (date début de contrat)</option>
                        <option value="4">Journalier en fin de contrat</option>
                    </select>
                </div>

                {{-- Unité (conditionnel) --}}
                <div x-show="rechercheType == '1'" x-cloak>
                    <label for="uniteid" class="block text-sm font-medium text-text-primary mb-2">
                        Unité
                    </label>
                    <select
                        name="uniteid"
                        id="uniteid"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-accent focus:border-transparent transition-all"
                    >
                        <option value="">-- Sélectionner --</option>
                        @foreach($data_unites as $unite)
                            <option value="{{ $unite->id }}">{{ $unite->label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date début (conditionnel) --}}
                <div x-show="rechercheType == '3' || rechercheType == '4'" x-cloak>
                    <label for="beginn" class="block text-sm font-medium text-text-primary mb-2">
                        Date début
                    </label>
                    <input
                        type="date"
                        name="beginn"
                        id="beginn"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-accent focus:border-transparent transition-all"
                    >
                </div>

                {{-- Date fin (conditionnel) --}}
                <div x-show="rechercheType == '3' || rechercheType == '4'" x-cloak>
                    <label for="endd" class="block text-sm font-medium text-text-primary mb-2">
                        Date fin
                    </label>
                    <input
                        type="date"
                        name="endd"
                        id="endd"
                        value="{{ date('Y-m-d') }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-accent focus:border-transparent transition-all"
                    >
                </div>
            </div>

            {{-- Bouton de soumission --}}
            <div class="flex justify-end pt-4">
                <button
                    type="submit"
                    class="px-8 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-200"
                >
                    <i class="fa fa-search mr-2"></i>
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    {{-- Résultats de recherche --}}
    <div class="bg-white rounded-lg shadow-lg-soft">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-text-primary">
                    Résultats de la recherche
                    @if($recherches && count($recherches) > 0)
                        <span class="ml-2 text-sm font-normal text-slate-500">({{ count($recherches) }} résultat{{ count($recherches) > 1 ? 's' : '' }})</span>
                    @endif
                </h2>

                {{-- Boutons d'export --}}
                @if($recherches && count($recherches) > 0)
                    <div class="flex space-x-3">
                        <a href="{{ route('excel_download', $code) }}" class="inline-flex items-center px-4 py-2 bg-primary-accent text-white font-medium rounded-lg hover:bg-plastica-blue transition-colors duration-200">
                            <i class="icon-folder mr-2"></i>
                            Export SAGE
                        </a>
                        <a href="{{ route('excel_download_quinzaine', $code) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            <i class="icon-refresh mr-2"></i>
                            Export Paie
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            @if(!$recherches || count($recherches) === 0)
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-full mb-4">
                        <i class="fa fa-search text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-2">Aucun résultat trouvé</h3>
                    <p class="text-text-secondary">Veuillez affiner vos critères de recherche</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Photo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Matricule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nom & Prénoms
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Unité
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Date d'embauche
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Date fin de contrat
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($recherches as $travailleur)
                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                {{-- Photo --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img
                                        src="{{ $travailleur->photo
                                            ? asset('rhassets/images/travailleurs/' . $travailleur->photo)
                                            : asset('rhassets/images/travailleurs/default.png') }}"
                                        alt="Photo {{ $travailleur->nom }}"
                                        class="rounded-full object-cover w-12 h-12"
                                    >
                                </td>

                                {{-- Matricule --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-text-primary">{{ $travailleur->matricule }}</span>
                                </td>

                                {{-- Nom & Prénoms --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-text-primary">{{ $travailleur->nom }} {{ $travailleur->prenom }}</span>
                                </td>

                                {{-- Unité --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-text-secondary">
                                        @foreach($data_unites as $unite)
                                            @if($unite->id == $travailleur->uniteid)
                                                {{ $unite->label }}
                                                @break
                                            @endif
                                        @endforeach
                                    </span>
                                </td>

                                {{-- Date d'embauche --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-text-secondary">
                                        {{ $travailleur->date_debut_contrat ? \Carbon\Carbon::parse($travailleur->date_debut_contrat)->format('d/m/Y') : '-' }}
                                    </span>
                                </td>

                                {{-- Date fin de contrat --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-text-secondary">
                                        {{ $travailleur->date_fin_contrat ? \Carbon\Carbon::parse($travailleur->date_fin_contrat)->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

{{-- Alpine.js component pour gérer la logique du formulaire --}}
<script>
function searchForm() {
    return {
        typeId: '',
        rechercheType: '',

        init() {
            // Initialisation si nécessaire
        }
    }
}
</script>

<style>
/* Masquer les éléments avec x-cloak avant que Alpine.js soit initialisé */
[x-cloak] {
    display: none !important;
}
</style>

@endsection
