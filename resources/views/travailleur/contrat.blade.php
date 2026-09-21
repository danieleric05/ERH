@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-text-primary">Télécharger le Contrat</h1>
                <p class="text-sm text-text-secondary mt-1">
                    Travailleur : <span class="font-semibold text-slate-700">{{ $edit_travailleur->nom }} {{ $edit_travailleur->prenoms_complets }}</span>
                    ({{ $edit_travailleur->matricule }})
                </p>
            </div>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('liste_tous_travailleurs') }}"
               class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg text-sm font-medium transition flex items-center gap-2 self-start md:self-auto">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent flex items-center gap-1">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <a href="{{ route('liste_tous_travailleurs') }}" class="hover:text-primary-accent">
                Travailleurs
            </a>
            <span>/</span>
            <span class="text-text-primary font-medium">Télécharger le contrat</span>
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
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('liste_tous_travailleurs') }}"
               class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('etapedeuxtravailleur', $edit_travailleur->id) }}"
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <i class="fa fa-user"></i> Voir fiche travailleur
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