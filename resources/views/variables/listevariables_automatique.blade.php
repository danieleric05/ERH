@extends('layouts.erh')
@section('content')
@php
    $moisNoms = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
@endphp

    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-text-primary mb-2">Variables automatiques</h1>
                <p class="text-sm text-text-secondary">Calculées depuis Santé (arrêts maladie), Autorisations (absences justifiées) et Sanctions — {{ $moisNoms[$mois] }} {{ $annee }}</p>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary mt-2">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary"><i class="fa fa-home"></i> Accueil</a>
                    <span>/</span><a href="{{ route('variables_paie', ['annee' => $annee, 'mois' => $mois]) }}" class="hover:text-text-primary">Variables</a>
                    <span>/</span><span class="text-text-primary font-semibold">Automatiques</span>
                </nav>
            </div>
            <form method="GET" action="{{ route('listevariables_automatique') }}" class="flex items-end gap-2">
                <select name="mois" class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white">
                    @foreach($moisNoms as $n => $lib)<option value="{{ $n }}" @selected($n == $mois)>{{ $lib }}</option>@endforeach
                </select>
                <input type="number" name="annee" value="{{ $annee }}" min="2019" max="2100" class="w-24 px-3 py-2 border border-slate-300 rounded-lg text-sm">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Afficher</button>
                <a href="{{ route('variables_paie', ['annee' => $annee, 'mois' => $mois]) }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium">Grille de paie</a>
            </form>
        </div>
    </div>

    @include('success')
    @include('errors')

    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden" x-data="tableSort()"><div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-900 text-white border-b">
                    <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Employé</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Variable</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold">Nombre de jours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variableA as $vari)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-3 text-sm font-semibold">{{ $vari->matricule }}</td>
                        <td class="px-6 py-3 text-sm text-text-primary">{{ $vari->employe }}</td>
                        <td class="px-6 py-3 text-sm text-text-secondary">{{ $vari->variable }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold">{{ $vari->nombre_jour }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500"><p class="text-lg">Aucune variable automatique pour {{ $moisNoms[$mois] }} {{ $annee }}</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
@endsection
