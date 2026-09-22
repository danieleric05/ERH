@extends('layouts.erh')
@section('content')
@php
    $moisNoms = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    $retour = route('variables_paie', ['annee' => $annee, 'mois' => $mois]);
    $nom = $travailleur ? trim($travailleur->nom . ' ' . $travailleur->prenoms_complets) : '(matricule inconnu)';
@endphp
<div class="p-6 max-w-3xl">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Modifier une variable</h1>
            <p class="text-sm text-text-secondary mt-1">{{ $moisNoms[$mois] }} {{ $annee }}</p>
        </div>
        <a href="{{ $retour }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium flex items-center gap-2"><i class="fa fa-arrow-left"></i> Retour</a>
    </div>

    @include('success')
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="mb-5 grid grid-cols-2 gap-4 text-sm">
            <div><div class="text-xs text-slate-500">Travailleur</div><div class="font-semibold">{{ $matricule }} — {{ $nom }}</div></div>
            <div><div class="text-xs text-slate-500">Variable</div><div class="font-semibold">{{ $def['libelle'] }} <span class="text-slate-400 font-normal">({{ $def['unite'] }})</span></div></div>
        </div>

        @if($calcul !== null)
            <div class="mb-4 p-3 bg-sky-50 border border-sky-200 rounded-lg text-sm text-sky-800">
                Valeur calculée depuis les autres modules : <strong>{{ $calcul }}</strong>.
                Une valeur saisie ci-dessous la remplace ; en supprimant la valeur, on revient au calcul.
            </div>
        @endif

        <form method="POST" action="{{ route('variables_paie_update') }}">
            @csrf
            <input type="hidden" name="matricule" value="{{ $matricule }}">
            <input type="hidden" name="annee" value="{{ $annee }}">
            <input type="hidden" name="mois" value="{{ $mois }}">
            <input type="hidden" name="code" value="{{ $code }}">

            <div class="space-y-3">
                @if($hebdo)
                    @for($s = 1; $s <= $nbSemaines; $s++)
                        @php $l = $enregistrees->get($s); @endphp
                        <div class="flex items-center gap-4">
                            <div class="w-64 text-sm text-slate-700">
                                Semaine {{ $s }}
                                @if($l && $l->periode_libelle)<span class="block text-xs text-slate-400">{{ $l->periode_libelle }}</span>@endif
                            </div>
                            <input type="number" step="0.01" min="0" name="valeurs[{{ $s }}]" value="{{ $l ? rtrim(rtrim(number_format($l->valeur, 2, '.', ''), '0'), '.') : '' }}"
                                   class="w-32 px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="—">
                            @if($l)<span class="text-xs px-2 py-1 rounded-full {{ $l->source === 'import' ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-700' }}">{{ $l->source === 'import' ? 'importé' : 'saisi' }}</span>@endif
                        </div>
                    @endfor
                @else
                    @php $l = $enregistrees->get(0); @endphp
                    <div class="flex items-center gap-4">
                        <div class="w-64 text-sm text-slate-700">Valeur du mois</div>
                        <input type="number" step="0.01" min="0" name="valeurs[0]" value="{{ $l ? rtrim(rtrim(number_format($l->valeur, 2, '.', ''), '0'), '.') : '' }}"
                               class="w-32 px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="—">
                        @if($l)<span class="text-xs px-2 py-1 rounded-full {{ $l->source === 'import' ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-700' }}">{{ $l->source === 'import' ? 'importé' : 'saisi' }}</span>@endif
                    </div>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-4">Laisser une case vide supprime la valeur enregistrée.</p>

            <div class="mt-6 flex gap-3">
                <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Enregistrer</button>
                <a href="{{ $retour }}" class="px-6 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
