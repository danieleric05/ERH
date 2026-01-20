@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des tenues</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Tenues</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('ajouter-tenue') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fa fa-plus"></i> Ajouter
                </a>
                <a href="{{ route('stock_tenues') }}" title="Voir le stock"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition">
                    <i class="fa fa-eye"></i> Stock
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
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom & Prénoms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Service</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Date réception</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Article reçu</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État tenue</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tenues ?? [] as $listedata)
                    @php
                        $travailleur = \App\Travailleur::where('id', $listedata->travailleurid)->first();
                        $service = \App\Services_tenue::where('id', $listedata->services)->first();
                        $articleRecu = \App\ArticleRecu::where('id', $listedata->tenuerecu)->first();
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary font-semibold">
                            {{ $travailleur->matricule ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-primary"
                            title="Matricule: {{ $travailleur->matricule ?? '-' }}">
                            {{ ($travailleur->nom ?? '') }} {{ ($travailleur->prenom ?? '') }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $service->label ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-text-secondary text-sm"
                            title="Créé le: {{ $listedata->created_at ?? '-' }}">
                            {{ $listedata->datereception ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm"
                            title="Tenue: {{ $listedata->detail_tenue ?? '-' }}; Chaussure: {{ $listedata->detail_chaussure ?? '-' }}">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $articleRecu->label ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($listedata->etat == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">BON</span>
                            @elseif($listedata->etat == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">MAUVAIS</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Change State Button --}}
                                @if($listedata->etat == 1)
                                    <a title="Changer l'état de la tenue"
                                       href="{{ url('chager_etat', $listedata->id) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa fa-exchange"></i>
                                    </a>
                                @endif

                                {{-- Edit Button --}}
                                <a title="Modifier"
                                   href="{{ url('edit/tenues/data') }}"
                                   data-id="{{ $listedata->id }}"
                                   id="edit"
                                   class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">
                            Aucune tenue disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('tenues.modal_edit')

@endsection