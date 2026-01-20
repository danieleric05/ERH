@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des autorisations</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Autorisations</span>
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
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Demandeur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Début</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fin</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Motif</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Etat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($liste_auto ?? [] as $auto)
                    @php
                        $trava = \App\Travailleur::where('id', $auto->demandeurid)->first();
                        $motif_labels = [
                            1 => 'Maladie',
                            2 => 'Convenance personnelle',
                            3 => 'Permissions exceptionnelles',
                            4 => 'Congés payés',
                            5 => 'Congés sans solde',
                            7 => 'Autres cas'
                        ];
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-sm"
                                  :title="($trava?->nom ?? '') . ' ' . ($trava?->prenom ?? '')">
                                {{ $trava?->matricule ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $auto->debut ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $auto->fin ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-primary text-sm"
                            :title="$auto->commentaire">
                            {{ $motif_labels[$auto->motif_absence] ?? 'Non spécifié' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($auto->statutid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Actif</span>
                            @elseif($auto->statutid == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactif</span>
                            @elseif($auto->statutid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Appliqué</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
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
                                    <a href="{{ route('autorisationvariable', $auto->id) }}"
                                       title="AJOUTER AUX VARIABLES"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                @elseif($auto->statutid == 3)
                                    <span class="p-2 text-slate-400 cursor-not-allowed"
                                          title="AUTORISATION DÉJÀ APPLIQUÉE">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-text-secondary">
                            Aucune autorisation disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('tenues.modal_edit')

@endsection