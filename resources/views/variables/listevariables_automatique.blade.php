@extends('layouts.erh')
@section('content')

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-text-primary mb-2">Absences, arrêts maladie et sanctions</h1>
        <p class="text-sm text-text-secondary">Valeurs enregistrées (fichier importé ou saisie) ou, à défaut, calculées depuis Santé, Autorisations et Sanctions.</p>
        <nav class="flex items-center space-x-2 text-sm text-text-secondary mt-2">
            <a href="{{ url('bienvenue') }}" class="hover:text-text-primary"><i class="fa fa-home"></i> Accueil</a>
            <span>/</span><span>Variables</span>
            <span>/</span><span class="text-text-primary font-semibold">Automatiques</span>
        </nav>
    </div>

    @include('success')
    @include('errors')

    @include('variables._paie_liste', ['routeListe' => 'listevariables_automatique', 'titre' => 'Présence'])
@endsection
