@extends('layouts.erh')
@section('content')
@php
    $hs = $variable->cause == 2;
    $retour = route($hs ? 'listevariables_heure_supp' : 'listevariables_manuelle');
@endphp
<div class="p-6 max-w-3xl">
    <div class="mb-6 flex items-start justify-between gap-4">
        <h1 class="text-3xl font-bold text-text-primary">{{ $hs ? 'Modifier des heures supplémentaires' : 'Modifier une variable manuelle' }}</h1>
        <a href="{{ $retour }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium flex items-center gap-2"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
    @include('success')
    @if($errors->any())<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <form method="POST" action="{{ route('variables_maj', $variable->id) }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
        @csrf
        <div>
            <div class="text-xs text-slate-500 mb-1">Travailleurs concernés (non modifiables)</div>
            <div class="flex flex-wrap gap-1">
                @forelse($travailleurs as $t)
                    <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full" title="{{ $t->nom }} {{ $t->prenoms_complets }}">{{ $t->matricule }} — {{ $t->nom }}</span>
                @empty
                    <span class="text-sm text-amber-700">Aucun travailleur retrouvé</span>
                @endforelse
            </div>
        </div>

        @if($hs)
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-1">Nombre d'heures</label>
                    <input required type="number" step="1" min="1" max="24" name="nbre_heure_hs" value="{{ old('nbre_heure_hs', $variable->nbre_heure_hs) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium mb-1">Date</label>
                    <input required type="date" name="date_hs" value="{{ old('date_hs', $variable->date_hs) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-1">Jour</label>
                    <select required name="type_variable" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-white">
                        @foreach(\App\Variables::JOURS as $k => $lib)<option value="{{ $k }}" @selected(old('type_variable', $variable->type_variable) == $k)>{{ $lib }}</option>@endforeach
                    </select></div>
                <div><label class="block text-sm font-medium mb-1">Cas</label>
                    <select required name="cas_variables" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-white">
                        @foreach(\App\Variables::CAS as $k => $lib)<option value="{{ $k }}" @selected(old('cas_variables', $variable->cas_variables) == $k)>{{ $lib }}</option>@endforeach
                    </select></div>
                <div><label class="block text-sm font-medium mb-1">Début</label>
                    <input required type="date" name="debut" value="{{ old('debut', $variable->debut) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium mb-1">Fin</label>
                    <input required type="date" name="fin" value="{{ old('fin', $variable->fin) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium mb-1">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-white">
                        <option value="">—</option>
                        <option value="1" @selected(old('periode', $variable->periode) == 1)>Nuit</option>
                        <option value="2" @selected(old('periode', $variable->periode) == 2)>Jour</option>
                    </select></div>
            </div>
        @endif

        <div><label class="block text-sm font-medium mb-1">Justification</label>
            <textarea name="justification" rows="3" maxlength="500" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('justification', $variable->justification) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="actif" value="1" @checked(old('actif', $variable->statutid == 1))> Variable active (décocher pour l'annuler)</label>

        <div class="flex gap-3">
            <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Enregistrer</button>
            <a href="{{ $retour }}" class="px-6 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium">Annuler</a>
        </div>
    </form>
</div>
@endsection
