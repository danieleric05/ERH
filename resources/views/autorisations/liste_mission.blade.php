@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des missions</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Missions</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-autorisation') }}"
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
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-4 py-4 text-left text-sm font-semibold">Demandeur</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Début</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Fin</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Pays</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Nuitées</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Journées</th>
                        <th class="px-4 py-4 text-center text-sm font-semibold">Transport</th>
                        <th class="px-4 py-4 text-center text-sm font-semibold">Etat</th>
                        <th class="px-4 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($liste_mission ?? [] as $auto)
                    @php
                        $trava = \App\Travailleur::where('id', $auto->demandeurid)->first();
                        $transport_labels = [
                            1 => 'Avion',
                            2 => 'Bateau',
                            3 => 'Train',
                            4 => 'Véhicule'
                        ];
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-4 py-4">
                            <span class="inline-block px-2 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-xs"
                                  :title="($trava?->nom ?? '') . ' ' . ($trava?->prenom ?? '')">
                                {{ $trava?->matricule ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-text-secondary text-sm">
                            {{ $auto->debut ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-text-secondary text-sm">
                            {{ $auto->fin ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-text-primary text-sm">
                            {{ $auto->pays ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-text-primary text-sm">
                            {{ $auto->nuitee ?? '0' }}
                        </td>
                        <td class="px-4 py-4 text-text-primary text-sm">
                            {{ $auto->journee ?? '0' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php
                                $transport = $auto->mode_transport;
                                $color_map = [
                                    1 => 'bg-blue-100 text-blue-800',
                                    2 => 'bg-red-100 text-red-800',
                                    3 => 'bg-red-100 text-red-800',
                                    4 => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $color_map[$transport] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $transport_labels[$transport] ?? 'Non spécifié' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($auto->statutid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Actif</span>
                            @elseif($auto->statutid == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                            @elseif($auto->statutid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Appliqué</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="#"
                                   title="MODIFIER"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <a href="#"
                                   title="ANNULER"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </a>

                                @if($auto->statutid == 1)
                                    <a href="{{ route('missionvariable', $auto->id) }}"
                                       title="AJOUTER AUX VARIABLES"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                @elseif($auto->statutid == 3)
                                    <span class="p-2 text-slate-400 cursor-not-allowed"
                                          title="MISSION DÉJÀ APPLIQUÉE">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-text-secondary">
                            Aucune mission disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('tenues.modal_edit')

@endsection