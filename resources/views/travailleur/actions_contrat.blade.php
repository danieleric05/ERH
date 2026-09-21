@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-text-primary">Télécharger le Document</h1>
                <p class="text-sm text-text-secondary mt-1">
                    Travailleur : <span class="font-semibold text-slate-700">{{ $edit->nom }} {{ $edit->prenoms_complets }}</span>
                    ({{ $edit->matricule }})
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
            <span class="text-text-primary font-medium">
                Télécharger un document
                @if($edit->etapeid == 3) de cessation @endif
                @if($edit->etapeid == 4) de certificat de travail @endif
                @if($edit->etapeid == 5) de déclaration CNPS @endif
            </span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Content Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-2xl font-bold text-text-primary mb-8 text-center">Télécharger le Document</h2>

        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row justify-center gap-4 flex-wrap mb-4">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('liste_tous_travailleurs') }}"
               class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <i class="fa fa-arrow-left"></i> Retour
            </a>

            <a href="{{ route('etapedeuxtravailleur', $edit->id) }}"
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <i class="fa fa-user"></i> Voir fiche travailleur
            </a>

            <!-- Contrat de travail habituel -->
            <a href="{{ $edit->contrat_url }}" target="_blank"
               class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <i class="fa fa-download"></i> {{ $edit->contrat_libelle }}
            </a>

            @if($edit->etapeid == 3)
                <!-- Cessation -->
                <a href="{{ route('telechargerContratCessassion',['id'=>$edit->id, 'download'=>'pdf']) }}" title="CESSATION" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Contrat de Cessation
                </a>

            @endif

            @if($edit->etapeid == 4)
                <!-- Certificat de Travail -->
                <a href="{{ route('telechargerContratCertificatTravail',['id'=>$edit->id, 'download'=>'pdf']) }}" target="_blank" title="CERTIFICAT DE TRAVAIL" class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Certificat de Travail
                </a>
            @endif

            @if($edit->etapeid == 5)
                <!-- Déclaration CNPS -->
                <a href="{{ route('telechargerContratDeclarationCnps',['id'=>$edit->id, 'download'=>'pdf']) }}" target="_blank" title="DECLARATION CNPS" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors text-center">
                    Télécharger Déclaration CNPS
                </a>
            @endif
        </div>
    </div>
</div>

@endsection