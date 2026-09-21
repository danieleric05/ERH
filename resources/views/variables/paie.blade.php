@extends('layouts.erh')
@section('content')
@php
    $moisNoms = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    $fmt = fn($v, $unite) => $unite === 'montant' ? number_format($v, 0, ',', ' ') : rtrim(rtrim(number_format($v, 2, ',', ''), '0'), ',');
@endphp

<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-text-primary">Variables de paie</h1>
                <p class="text-sm text-text-secondary mt-1">{{ $moisNoms[$mois] }} {{ $annee }} — {{ count($lignes) }} travailleur(s) avec au moins une variable</p>
            </div>
            <a href="{{ url('bienvenue') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg text-sm font-medium transition flex items-center gap-2 self-start">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent"><i class="fa fa-home"></i> Accueil</a>
            <span>/</span><a href="{{ route('variables_paie') }}" class="hover:text-primary-accent">Variables</a>
            <span>/</span><span class="text-text-primary font-medium">Variables de paie</span>
        </nav>
    </div>

    @include('success')
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    {{-- Filtres et actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4">
        <form method="GET" action="{{ route('variables_paie') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Mois</label>
                <select name="mois" class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white">
                    @foreach($moisNoms as $n => $lib)<option value="{{ $n }}" @selected($n == $mois)>{{ $lib }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Année</label>
                <input type="number" name="annee" value="{{ $annee }}" min="2019" max="2100" class="w-24 px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-slate-600 mb-1">Rechercher (matricule, nom)</label>
                <input type="text" name="q" value="{{ $recherche }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm uppercase">
            </div>
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"><i class="fa fa-search"></i> Afficher</button>
            <a href="{{ route('variables_paie_export', ['annee' => $annee, 'mois' => $mois]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium flex items-center gap-2"><i class="fa fa-file-excel-o"></i> Exporter Excel</a>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        {{-- Saisie --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Ajouter ou corriger une variable</h2>
            <form method="POST" action="{{ route('variables_paie_store') }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <input type="hidden" name="annee" value="{{ $annee }}"><input type="hidden" name="mois" value="{{ $mois }}">
                <div><label class="block text-xs text-slate-600 mb-1">Matricule</label>
                    <input required name="matricule" value="{{ old('matricule') }}" placeholder="E00123" class="w-28 px-3 py-2 border border-slate-300 rounded-lg text-sm uppercase"></div>
                <div class="flex-1 min-w-[160px]"><label class="block text-xs text-slate-600 mb-1">Variable</label>
                    <select required name="code" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white">
                        @foreach($codes as $c => $def)<option value="{{ $c }}">{{ $def['libelle'] }} ({{ $def['unite'] }})</option>@endforeach
                    </select></div>
                <div><label class="block text-xs text-slate-600 mb-1">Semaine <span class="text-slate-400">(1-5, si hebdo)</span></label>
                    <input type="number" name="semaine" min="1" max="5" value="1" class="w-20 px-3 py-2 border border-slate-300 rounded-lg text-sm"></div>
                <div><label class="block text-xs text-slate-600 mb-1">Valeur</label>
                    <input required type="number" step="0.01" min="0" name="valeur" class="w-28 px-3 py-2 border border-slate-300 rounded-lg text-sm"></div>
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Enregistrer</button>
            </form>
        </div>

        {{-- Import Excel --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Importer un fichier Excel de variables</h2>
            <form method="POST" action="{{ route('variables_paie_import') }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="flex-1 min-w-[200px]"><input required type="file" name="fichier" accept=".xlsx" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white"></div>
                <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="simulation" value="1" checked> Simulation</label>
                <button class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white rounded-lg text-sm font-medium"><i class="fa fa-upload"></i> Importer</button>
            </form>
            <p class="text-xs text-slate-500 mt-2">Une feuille par mois (« SEPT 2026 »…). Réimporter met à jour sans créer de doublons et n'écrase pas les saisies manuelles.</p>
        </div>
    </div>

    {{-- Grille --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-800 text-white text-xs uppercase">
                <tr>
                    <th class="px-3 py-3 text-left sticky left-0 bg-slate-800">Matricule</th>
                    <th class="px-3 py-3 text-left">Nom et prénoms</th>
                    @foreach($codesGrille as $c => $def)<th class="px-3 py-3 text-right whitespace-nowrap" title="{{ $def['unite'] }}">{{ $def['libelle'] }}</th>@endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($lignes as $l)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 font-semibold sticky left-0 bg-white">{{ $l['matricule'] }}</td>
                        <td class="px-3 py-2 {{ $l['connu'] ? '' : 'text-amber-700 italic' }}">{{ $l['nom'] }}</td>
                        @foreach($codesGrille as $c => $def)
                            @php $v = $l['valeurs'][$c] ?? null; $src = $l['sources'][$c] ?? null; @endphp
                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                @if($v !== null)
                                    <span class="{{ $src === 'auto' ? 'text-sky-700' : 'font-medium text-slate-800' }}" title="{{ $src === 'auto' ? 'Calculé depuis les autres modules' : 'Enregistré' }}">{{ $fmt($v, $def['unite']) }}@if($src === 'auto')<sup class="text-sky-500"> auto</sup>@endif</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ 2 + count($codesGrille) }}" class="px-3 py-10 text-center text-slate-500">Aucune variable pour {{ $moisNoms[$mois] }} {{ $annee }}.</td></tr>
                @endforelse
            </tbody>
            @if($lignes)
            <tfoot class="bg-slate-100 text-xs font-semibold">
                <tr><td class="px-3 py-2" colspan="2">Total</td>
                    @foreach($codesGrille as $c => $def)<td class="px-3 py-2 text-right">{{ isset($totaux[$c]) ? $fmt($totaux[$c], $def['unite']) : '' }}</td>@endforeach
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <p class="text-xs text-slate-500 mt-3"><span class="text-sky-700">Valeur en bleu « auto »</span> : calculée depuis Santé (arrêts maladie), Autorisations (absences justifiées) et Sanctions. Une valeur enregistrée remplace toujours le calcul.</p>
</div>
@endsection
