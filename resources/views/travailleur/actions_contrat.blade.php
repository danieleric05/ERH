@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Télécharger le Document</h1>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Recrutement</span>
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
        <div class="flex flex-col md:flex-row justify-center gap-4 flex-wrap">
            <a href="{{ route('liste_tous_travailleurs') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors text-center">
                Retour
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