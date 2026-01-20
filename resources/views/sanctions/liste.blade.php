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
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Demandeur</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Fautif(s)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Motif</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sanction appliquée</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Résultat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">États</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date sanction</th>
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
                                @foreach(unserialize($vari->employeid) as $matricule)
                                    @php
                                        $travailleur = \App\Travailleur::where('matricule', $matricule)->first();
                                    @endphp
                                    @if($travailleur)
                                        <span title="{{ $travailleur->nom }} {{ $travailleur->prenom }}" class="px-2 py-1 text-xs font-semibold text-slate-800 bg-slate-100 rounded-full">
                                            {{ $matricule }}
                                        </span>
                                    @endif
                                @endforeach
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

    @include('tenues.modal_edit')

@endsection