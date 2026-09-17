@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- Header with Breadcrumb --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Paramètres</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-primary font-semibold">Administration</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Messages --}}
        @include('success')
        @include('errors')

        {{-- Settings Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Catégories --}}
            <a href="{{ url('liste-categorie') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-tags text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Catégories</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les catégories de l'application</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Départements --}}
            <a href="{{ url('liste-departement') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-building text-green-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Départements</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les départements</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Équipes --}}
            <a href="{{ url('liste-equipe') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-users text-purple-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Équipes</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les équipes</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Fonctions --}}
            <a href="{{ url('liste-fonction') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-briefcase text-yellow-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Fonctions</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les fonctions/métiers</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Niveaux d'études --}}
            <a href="{{ url('liste-niveau-etude') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-graduation-cap text-indigo-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Niveaux d'études</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les niveaux d'études</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Pays --}}
            <a href="{{ url('liste-pays') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-globe text-red-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Pays</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les pays</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

            {{-- Unités --}}
            <a href="{{ url('liste-unite') }}" class="bg-white rounded-lg shadow-lg-soft p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fa fa-cube text-orange-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text-primary">Unités</h3>
                </div>
                <p class="text-sm text-text-secondary mb-4">Gérer les unités de mesure</p>
                <span class="text-xs font-medium text-primary-accent">Voir plus →</span>
            </a>

        </div>

    </div>

@endsection
