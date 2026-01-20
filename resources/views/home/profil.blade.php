@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Mon Profil</h1>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('bienvenue') }}" class="hover:text-primary-accent">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <span class="text-text-primary font-medium">Mon Profil</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Profile Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-lg-soft p-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-text-primary">{{ Auth::user()->name ?? 'Utilisateur' }}</h2>
                <p class="text-sm text-text-secondary">{{ Auth::user()->pseudo ?? 'N/A' }}</p>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <div class="mb-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Statut</p>
                    <div class="mt-2">
                        @if(Auth::user()->statut_id == 1)
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Actif</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Inactif</span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Rôle</p>
                    <p class="mt-2 text-sm text-text-primary font-medium">{{ Auth::user()->idrole ?? 'N/A' }}</p>
                </div>
            </div>

            <a href="{{ url('add-mdp') }}" class="w-full mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-center block transition">
                Modifier le mot de passe
            </a>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg-soft p-6">
            <h3 class="text-lg font-bold text-text-primary mb-6">Informations Personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Nom d'utilisateur</p>
                    <p class="text-sm text-text-primary">{{ Auth::user()->pseudo ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Nom complet</p>
                    <p class="text-sm text-text-primary">{{ Auth::user()->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Email</p>
                    <p class="text-sm text-text-primary">{{ Auth::user()->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">ID Utilisateur</p>
                    <p class="text-sm text-text-primary">{{ Auth::user()->id ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-6 pt-6">
                <h4 class="text-base font-semibold text-text-primary mb-4">Paramètres de Sécurité</h4>

                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div>
                        <p class="text-sm font-medium text-text-primary">Mot de passe</p>
                        <p class="text-xs text-text-secondary mt-1">Gérez votre mot de passe</p>
                    </div>
                    <a href="{{ url('add-mdp') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition">
                        Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
