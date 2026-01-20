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
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Infirmier</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Travailleur</th>
                        @if(Auth::user()->idrole == 4)
                            <th class="px-6 py-4 text-left text-sm font-semibold">Consultation</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Prescription</th>
                        @endif
                        <th class="px-6 py-4 text-center text-sm font-semibold">Arrêt travail</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Début</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Fin</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Enregistré le</th>
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