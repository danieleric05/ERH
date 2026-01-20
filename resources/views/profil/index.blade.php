@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- Header with Breadcrumb --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Mon Profil</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-primary font-semibold">Mon Profil</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Messages --}}
        @include('success')
        @include('errors')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Profile Card --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-plastica-blue to-primary-accent"></div>
                    <div class="px-6 py-6 -mt-16 relative">
                        <div class="flex flex-col md:flex-row items-start md:items-end gap-4 mb-8">
                            <img src="{{ asset('rhassets/images/images.png') }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold text-text-primary">{{ Auth::user()->name ?? Auth::user()->pseudo ?? 'Utilisateur' }}</h2>
                                <p class="text-text-secondary mt-1">{{ Auth::user()->email ?? 'Email non fourni' }}</p>
                                <div class="mt-3 flex gap-2 flex-wrap">
                                    @php
                                        $roleNames = [
                                            1 => 'Administrateur',
                                            2 => 'Assistante RH',
                                            4 => 'Médical',
                                            5 => 'Visiteur',
                                            6 => 'RH Junior'
                                        ];
                                        $roleBadgeColor = [
                                            1 => 'bg-red-100 text-red-800',
                                            2 => 'bg-purple-100 text-purple-800',
                                            4 => 'bg-green-100 text-green-800',
                                            5 => 'bg-blue-100 text-blue-800',
                                            6 => 'bg-yellow-100 text-yellow-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $roleBadgeColor[Auth::user()->idrole] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $roleNames[Auth::user()->idrole] ?? 'Rôle inconnu' }}
                                    </span>
                                    @if(Auth::user()->statut_id == 1)
                                        <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Actif</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Inactif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-6">
                            <h3 class="text-lg font-bold text-text-primary mb-6">Informations du compte</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fa fa-user text-primary-accent text-lg"></i>
                                        <label class="text-sm font-medium text-text-secondary">Nom d'utilisateur</label>
                                    </div>
                                    <p class="text-text-primary font-semibold text-lg">{{ Auth::user()->pseudo ?? '-' }}</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fa fa-envelope text-primary-accent text-lg"></i>
                                        <label class="text-sm font-medium text-text-secondary">Email</label>
                                    </div>
                                    <p class="text-text-primary font-semibold text-lg">{{ Auth::user()->email ?? '-' }}</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fa fa-briefcase text-primary-accent text-lg"></i>
                                        <label class="text-sm font-medium text-text-secondary">Rôle</label>
                                    </div>
                                    <p class="text-text-primary font-semibold text-lg">{{ $roleNames[Auth::user()->idrole] ?? 'Rôle inconnu' }}</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fa fa-check-circle text-primary-accent text-lg"></i>
                                        <label class="text-sm font-medium text-text-secondary">Statut du compte</label>
                                    </div>
                                    <p class="text-text-primary font-semibold text-lg">
                                        @if(Auth::user()->statut_id == 1)
                                            <span class="text-green-600">Actif</span>
                                        @else
                                            <span class="text-red-600">Inactif</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
                    <div class="bg-gradient-to-r from-plastica-blue to-primary-accent px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fa fa-cog"></i> Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ url('logout') }}" class="block w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-center">
                            <i class="fa fa-sign-out mr-2"></i> Déconnexion
                        </a>
                        <a href="{{ url('bienvenue') }}" class="block w-full px-4 py-3 bg-primary-accent text-white rounded-lg hover:bg-plastica-blue transition font-medium text-center">
                            <i class="fa fa-arrow-left mr-2"></i> Retour au dashboard
                        </a>
                    </div>
                </div>

                {{-- Quick Info Cards --}}
                <div class="mt-6 space-y-4">
                    <div class="bg-white rounded-lg shadow-lg-soft p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-text-secondary font-medium">ID Utilisateur</span>
                            <span class="text-lg font-bold text-text-primary">{{ Auth::id() }}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg-soft p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-text-secondary font-medium">Dernière connexion</span>
                            <span class="text-sm text-text-primary font-semibold">{{ Auth::user()->derniere_cnx ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
