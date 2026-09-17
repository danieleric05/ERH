@extends('layouts.erh')
@section('content')

    <div class="bg-white rounded-lg shadow-lg-soft p-8 text-center">
        <h1 class="text-2xl font-bold text-text-primary mb-2">Erreur 277</h1>
        <p class="text-text-secondary mb-6">Une erreur est survenue lors du traitement de votre demande.</p>
        <a href="{{ url('bienvenue') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary-accent text-white font-semibold rounded-lg hover:bg-plastica-blue transition">
            <i class="fa fa-home"></i> Retour à l'accueil
        </a>
    </div>

@endsection
