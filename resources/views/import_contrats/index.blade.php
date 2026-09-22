@extends('layouts.erh')
@section('content')
@php
    $couleurs = ['créé' => 'bg-green-100 text-green-800', 'mis à jour' => 'bg-blue-100 text-blue-800', 'reconduit' => 'bg-indigo-100 text-indigo-800',
                 'historisé' => 'bg-slate-200 text-slate-700', 'inchangé' => 'bg-slate-100 text-slate-500', 'rejeté' => 'bg-red-100 text-red-800'];
@endphp
<div class="p-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Import des travailleurs et contrats</h1>
            <p class="text-sm text-text-secondary mt-1">Stagiaires, CDD, CDI et journaliers, à partir du Répertoire CDD.</p>
        </div>
        <a href="{{ route('import_contrats_modele') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium flex items-center gap-2 self-start">
            <i class="fa fa-download"></i> Télécharger le modèle Excel
        </a>
    </div>

    @include('success')
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form method="POST" action="{{ route('import_contrats_post') }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[260px]">
                <label class="block text-sm font-medium mb-1">Fichier Excel (.xlsx)</label>
                <input required type="file" name="fichier" accept=".xlsx" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="simulation" value="1" checked> Simulation (rien n'est enregistré)</label>
            <button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"><i class="fa fa-upload"></i> Lancer</button>
        </form>
        <p class="text-xs text-slate-500 mt-3">Commencez toujours par une simulation, vérifiez le rapport, puis relancez avec la case décochée. Réimporter le même fichier ne crée aucun doublon.</p>
    </div>

    @if($rapport)
        @php $t = $rapport['total']; @endphp
        <div class="mb-3 p-3 rounded-lg text-sm font-medium {{ $simulation ? 'bg-amber-50 border border-amber-200 text-amber-800' : 'bg-green-50 border border-green-200 text-green-800' }}">
            {{ $simulation ? 'SIMULATION : aucune donnée n\'a été enregistrée.' : 'Import terminé : les données ont été enregistrées.' }}
        </div>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-4">
            @foreach(['crees' => ['Créés', 'créé'], 'mis_a_jour' => ['Mis à jour', 'mis à jour'], 'reconduits' => ['Reconduits', 'reconduit'], 'historises' => ['Historisés', 'historisé'], 'inchanges' => ['Inchangés', 'inchangé'], 'rejetes' => ['Rejetés', 'rejeté']] as $k => [$lib, $st])
                <div class="rounded-lg p-3 text-center {{ $couleurs[$st] }}"><div class="text-2xl font-bold">{{ $t[$k] }}</div><div class="text-xs">{{ $lib }}</div></div>
            @endforeach
        </div>
        @if($csv)<p class="mb-3 text-sm"><a class="text-blue-700 underline" href="{{ route('import_contrats_rapport', $csv) }}"><i class="fa fa-file-excel-o"></i> Télécharger le rapport détaillé (CSV)</a></p>@endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-800 text-white text-xs uppercase">
                    <tr><th class="px-3 py-3 text-left">Ligne</th><th class="px-3 py-3 text-left">Résultat</th><th class="px-3 py-3 text-left">Matricule</th><th class="px-3 py-3 text-left">Nom</th><th class="px-3 py-3 text-left">Détail</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rapport['lignes'] as $l)
                        <tr class="align-top hover:bg-slate-50">
                            <td class="px-3 py-2 text-slate-500">{{ $l['ligne'] }}</td>
                            <td class="px-3 py-2"><span class="px-2 py-1 text-xs font-semibold rounded-full {{ $couleurs[$l['statut']] ?? '' }}">{{ $l['statut'] }}</span></td>
                            <td class="px-3 py-2 font-semibold">{{ $l['matricule'] ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $l['nom'] }}</td>
                            <td class="px-3 py-2 text-xs">
                                @if($l['erreur'])<div class="text-red-700 font-medium">{{ $l['erreur'] }}</div>@endif
                                @foreach($l['avertissements'] as $a)<div class="text-amber-700">⚠ {{ $a }}</div>@endforeach
                                @foreach($l['changements'] as $c)<div class="text-slate-600">· {{ $c }}</div>@endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
