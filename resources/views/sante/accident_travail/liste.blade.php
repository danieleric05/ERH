@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des accidents de travail</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Santé</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Accidents de Travail</span>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold">Travailleur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Cause</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Prescription</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Arrêt travail</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($listeAT ?? [] as $listedata)
                    @php
                        $travailleur = \App\Travailleur::where('id', $listedata->travailleurid)->first();
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary text-sm">
                            {{ ($travailleur->nom ?? '') }} {{ ($travailleur->prenom ?? '') }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->cause ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->prescription ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="flex flex-col gap-2">
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">
                                    Début: {{ $listedata->debut_arret ?? '-' }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                    Fin: {{ $listedata->fin_arret ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->datepub ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($listedata->statutid == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">TRAITÉ</span>
                            @elseif($listedata->statutid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">EN ATTENTE</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if((Auth::user()->idrole == 1 || Auth::user()->idrole == 2) && $listedata->statutid == 1)
                                    <a href="{{ route('accident_travail_traiter', $listedata->id) }}"
                                       title="Marquer comme traité"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @elseif($listedata->statutid == 2)
                                    <span class="p-2 text-slate-400 cursor-not-allowed" title="Déjà traité">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">
                            Aucun accident de travail disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('sante.accident_travail.modal_edit')

@endsection