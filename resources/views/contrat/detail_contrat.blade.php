@extends('layouts.erh')
@section('content')

    @php
        $travailleur = \App\Travailleur::find($id);
    @endphp

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-text-primary mb-4">Détail du contrat</h1>
            <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                    <i class="fa fa-home"></i> Accueil
                </a>
                <span class="text-text-secondary">/</span>
                <span>Contrat</span>
                <span class="text-text-secondary">/</span>
                <span class="text-text-primary font-semibold">Détail du contrat</span>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-start-2 bg-white rounded-lg shadow-lg-soft overflow-hidden">
            <div class="bg-primary-accent px-6 py-4">
                <h4 class="text-white font-bold">{{ optional($travailleur)->prenom }} {{ optional($travailleur)->nom }}</h4>
            </div>
            <div class="p-6 text-center">
                <img src="{{ asset('rhassets/images/images.png') }}" class="w-24 h-24 rounded-full mx-auto mb-4" alt="profile-image">
                <p class="text-text-secondary text-sm mb-4">{{ optional($travailleur)->description }}</p>
                <hr class="border-slate-200 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-bold text-text-primary">
                            @foreach($data_unites as $unite)
                                @if($unite->id == optional($travailleur)->uniteid)
                                    {{ $unite->label }}
                                @endif
                            @endforeach
                        </h5>
                        <small class="text-text-secondary">Unité</small>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-text-primary">
                            @foreach($data_equipes as $equipe)
                                @if($equipe->id == optional($travailleur)->equipeid)
                                    {{ $equipe->label }}
                                @endif
                            @endforeach
                        </h5>
                        <small class="text-text-secondary">Équipe</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Période des contrats -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-text-primary">Période des contrats</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Action</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Prénom</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Date de début de contrat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Date de fin de contrat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Télécharger</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($infoContrat as $info)
                    @php $t = \App\Travailleur::find($info->travailleurid); @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            @if($info->actionid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-800 text-white">Cessation</span>
                            @elseif($info->actionid == 4)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-800 text-white">Certificat</span>
                            @elseif($info->actionid == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-800 text-white">Reconduire</span>
                            @elseif($info->actionid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-slate-800 text-white">Premier Contrat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-text-primary">{{ optional($t)->matricule }}</td>
                        <td class="px-6 py-4 text-text-primary">{{ optional($t)->nom }}</td>
                        <td class="px-6 py-4 text-text-primary">{{ optional($t)->prenom }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $info->debut_contrat }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $info->fin_contrat }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="p-2 text-slate-400" title="Non disponible">
                                <i class="icon-doc" aria-hidden="true"></i>
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">Aucune période de contrat</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Changement d'unité -->
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-text-primary">Changement d'unité</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-900 text-white border-b">
                            <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nom</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Prénom</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Unité</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Date d'arrivée</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($infoHistUnite as $hist)
                        @php $t = \App\Travailleur::find($hist->travailleurid); @endphp
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-text-primary">{{ optional($t)->matricule }}</td>
                            <td class="px-6 py-4 text-text-primary">{{ optional($t)->nom }}</td>
                            <td class="px-6 py-4 text-text-primary">{{ optional($t)->prenom }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $hist->date_choix }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $hist->date_choix }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-secondary">Aucun changement d'unité</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gestion des tenues -->
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-text-primary">Gestion des tenues</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-900 text-white border-b">
                            <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nom</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Tenues reçues</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Reçu le</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($infoTenues as $hist)
                        @php $t = \App\Travailleur::find($hist->travailleurid); @endphp
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-text-primary">{{ optional($t)->matricule }}</td>
                            <td class="px-6 py-4 text-text-primary">{{ optional($t)->nom }} {{ optional($t)->prenom }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $hist->date_choix }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $hist->date_choix }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-secondary">Aucune tenue enregistrée</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
