@extends('layouts.erh')
@section('content')
<div class="p-6 max-w-3xl">
    <div class="mb-6 flex items-start justify-between gap-4">
        <h1 class="text-3xl font-bold text-text-primary">Modifier une variable</h1>
        <a href="{{ route('listevariables_autres_variables') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium flex items-center gap-2"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>
    @include('success')
    @if($errors->any())<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <form method="POST" action="{{ route('autres_variables_maj', $variable->id) }}" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
        @csrf
        <div>
            <div class="text-xs text-slate-500 mb-1">Travailleurs concernés (non modifiables)</div>
            <div class="flex flex-wrap gap-1">
                @forelse($travailleurs as $t)
                    <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">{{ $t->matricule }} — {{ $t->nom }}</span>
                @empty
                    <span class="text-sm text-slate-500">Aucun employé indiqué</span>
                @endforelse
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Variable</label>
                <select required name="cas" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-white">
                    @foreach(\App\AutresVariables::CAS as $k => $lib)<option value="{{ $k }}" @selected(old('cas', $variable->cas) == $k)>{{ $lib }}</option>@endforeach
                </select></div>
            <div><label class="block text-sm font-medium mb-1">Montant (F)</label>
                <input required type="number" step="1" min="0" name="montant" value="{{ old('montant', $variable->montant) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">Date</label>
                <input required type="date" name="date_variable" value="{{ old('date_variable', $variable->date_variable) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></div>
        </div>
        <div><label class="block text-sm font-medium mb-1">Justification</label>
            <textarea name="justification" rows="3" maxlength="500" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('justification', $variable->justification) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="actif" value="1" @checked(old('actif', $variable->statutid == 1))> Variable active (décocher pour l'annuler)</label>
        <div class="flex gap-3">
            <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Enregistrer</button>
            <a href="{{ route('listevariables_autres_variables') }}" class="px-6 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium">Annuler</a>
        </div>
    </form>
</div>
@endsection
