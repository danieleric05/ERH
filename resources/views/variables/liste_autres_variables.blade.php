@extends('layouts.erh')
@section('content')

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-2">Autres variables</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary"><i class="fa fa-home"></i> Accueil</a>
                    <span>/</span><span>Variables</span>
                    <span>/</span><span class="text-text-primary font-semibold">Autres variables</span>
                </nav>
            </div>
            <a href="{{ url('ajouter-autres-variables') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fa fa-plus"></i> Ajouter
            </a>
        </div>
    </div>

    @include('success')
    @include('errors')

    @include('variables._paie_liste', ['routeListe' => 'listevariables_autres_variables', 'titre' => 'Primes, rappels et retenues'])

    <h2 class="text-lg font-semibold text-text-primary mb-3">Saisies individuelles</h2>
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden"><div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-900 text-white border-b">
                    <th class="px-6 py-4 text-left text-sm font-semibold">Variable</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Employés</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold">Montant</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Date</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($autres as $vari)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-3 text-sm font-medium" title="{{ $vari->justification }}">{{ $vari->cas_libelle }}</td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex flex-wrap gap-1">
                                @forelse($vari->employes as $eid)
                                    @php $t = $employes->get($eid); @endphp
                                    <span title="{{ $t ? $t->nom . ' ' . $t->prenoms_complets : 'Travailleur introuvable' }}" class="px-2 py-1 text-xs font-semibold {{ $t ? 'text-gray-800 bg-gray-100' : 'text-amber-800 bg-amber-100' }} rounded-full">{{ $t->matricule ?? '#' . $eid }}</span>
                                @empty
                                    <span class="text-slate-400 text-xs">Aucun employé indiqué</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-right font-semibold">{{ number_format($vari->montant, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-3 text-sm text-text-secondary">{{ $vari->date_variable }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($vari->statutid == 1)
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('autres_variables_modifier', $vari->id) }}" title="Modifier" class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition"><i class="fa fa-edit"></i></a>
                                    @if($vari->statutid == 1)
                                        <form method="POST" action="{{ route('autres_variables_annuler', $vari->id) }}" onsubmit="return confirm('Annuler cette variable ?')" class="inline">
                                            @csrf
                                            <button type="submit" title="Annuler" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"><i class="fa fa-ban"></i></button>
                                        </form>
                                    @endif
                                </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500"><p class="text-lg">Aucune variable enregistrée</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
@endsection
