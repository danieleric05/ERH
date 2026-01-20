@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des heures supplémentaires</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Autorisations</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Heures Supplémentaires</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-heure-supplementaire') }}"
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
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Employés</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Nombre d'heures</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $allEmployerIds = [];
                            foreach ($variableHS ?? [] as $var) {
                                $allEmployerIds = array_merge($allEmployerIds, unserialize($var->employer_hs));
                            }
                            $allEmployers = \App\Travailleur::whereIn('id', array_unique($allEmployerIds))->get()->keyBy('id');
                        @endphp
                        @forelse($variableHS ?? [] as $vari)
                            <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(unserialize($vari->employer_hs) as $employerId)
                                            @php
                                                $travailleur = $allEmployers->get($employerId);
                                            @endphp
                                            @if($travailleur)
                                                <span title="{{ $travailleur->nom ?? '' }} {{ $travailleur->prenom ?? '' }}" class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                                                    {{ $travailleur->matricule ?? '' }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-text-primary font-semibold">
                                    {{ $vari->nbre_heure_hs ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $vari->date_hs ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($vari->statutid == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Actif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Edit Button --}}
                                        <a title="Modifier"
                                           href="#"
                                           class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a title="Annuler"
                                           href="#"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"
                                           class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucune heure supplémentaire trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('tenues.modal_edit')

@endsection