@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Télécharger le Contrat</h1>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Recrutement</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Étape 3 : Télécharger le contrat</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Content Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-2xl font-bold text-text-primary mb-8 text-center">Étape 3 : Télécharger le contrat</h2>

        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row justify-center gap-4 mb-8">
            <a href="{{ route('liste_tous_travailleurs') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors text-center">
                Retour
            </a>

            @if($edit_travailleur->idtype_contrat == 2)
                <!-- CDD -->
                <a href="{{ route('telechargerContratCDD',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}" target="_blank" title="CONTRAT CDD" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Contrat CDD
                </a>
            @elseif($edit_travailleur->idtype_contrat == 3)
                <!-- CDI -->
                <a href="{{ route('telechargerContratCDI',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}" target="_blank" title="CONTRAT CDI" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Contrat CDI
                </a>
            @else
                <!-- Journalier (default) -->
                <a href="{{ route('telechargerContratJournalier',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}" target="_blank" title="CONTRAT JOURNALIER" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Contrat Journalier
                </a>
            @endif
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-2">Information</h3>
            <p class="text-blue-800 text-sm">
                Le document sera ouvert dans un nouvel onglet. Vous pourrez l'imprimer ou le télécharger selon vos besoins.
            </p>
        </div>
    </div>
</div>

@endsection