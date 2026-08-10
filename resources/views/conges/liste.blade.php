@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des congés</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Congés</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('calendrier_conges') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                    <i class="fa fa-calendar"></i> Calendrier
                </a>
                <a href="{{ route('ajouterConges') }}"
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
                        <th class="px-6 py-4 text-left text-sm font-semibold">Travailleur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Début</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fin</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Jours</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Etat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($liste_conges ?? [] as $conge)
                    @php
                        $travailleur = $travailleursById->get($conge->travailleurid);
                        $type_labels = [
                            1 => 'Congé annuel',
                            2 => 'Congé maladie',
                            3 => 'Congé maternité/paternité',
                            4 => 'Congé sans solde',
                            5 => 'Congé exceptionnel',
                        ];
                    @endphp
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4" title="{{ $travailleur->nom ?? '' }} {{ $travailleur->prenom ?? '' }}">
                            <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-800 font-bold text-sm">
                                {{ $travailleur->matricule ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-text-primary text-sm" title="{{ $conge->justification }}">
                            {{ $type_labels[$conge->type_conge] ?? 'Non spécifié' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $conge->debut }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $conge->fin }}</td>
                        <td class="px-6 py-4 text-center text-text-primary text-sm">{{ $conge->nombre_jours }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($conge->statutid == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                            @elseif($conge->statutid == 2)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Validé</span>
                            @elseif($conge->statutid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Refusé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($conge->statutid == 1)
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('validerConge', $conge->id) }}" title="Valider"
                                       class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa fa-check-circle"></i>
                                    </a>
                                    <a href="{{ route('refuserConge', $conge->id) }}" title="Refuser"
                                       onclick="return confirm('Êtes-vous sûr de vouloir refuser cette demande ?')"
                                       class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fa fa-times-circle"></i>
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">
                            Aucun congé disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
