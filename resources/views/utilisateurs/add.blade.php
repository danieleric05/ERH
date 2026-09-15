@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter un Utilisateur</h1>
            <a href="{{ route('listeutilisateurs') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Administration</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter un utilisateur</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Formulaire d'ajout d'utilisateur</h2>

        <form action="{{ route('post_utilisateur') }}" method="POST">
            @csrf

            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-text-primary mb-2">Nom complet</label>
                        <input required type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-text-primary mb-2">Email</label>
                        <input required type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pseudo" class="block text-sm font-medium text-text-primary mb-2">Pseudo (identifiant de connexion)</label>
                        <input required type="text" name="pseudo" id="pseudo" value="{{ old('pseudo') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-text-primary mb-2">Mot de passe</label>
                        <input required type="text" name="password" id="password"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="idrole" class="block text-sm font-medium text-text-primary mb-2">Rôle</label>
                        <select required name="idrole" id="idrole"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($data_roles as $role)
                                <option value="{{ $role->id }}" {{ old('idrole') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="idunite" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                        <select required name="idunite" id="idunite"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($data_unites as $unite)
                                <option value="{{ $unite->id }}" {{ old('idunite') == $unite->id ? 'selected' : '' }}>{{ $unite->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="contact" class="block text-sm font-medium text-text-primary mb-2">Contact</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="fonction" class="block text-sm font-medium text-text-primary mb-2">Fonction</label>
                <input type="text" name="fonction" id="fonction" value="{{ old('fonction') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fa fa-check"></i> Enregistrer
                </button>
                <a href="{{ route('listeutilisateurs') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-200 text-text-primary rounded-lg hover:bg-slate-300 transition font-semibold">
                    <i class="fa fa-list"></i> Liste
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
