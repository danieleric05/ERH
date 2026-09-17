@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Inscription d'un travailleur</h1>
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span class="text-text-secondary">/</span>
            <span>Recrutement</span>
            <span class="text-text-secondary">/</span>
            <span class="text-text-primary font-semibold">Inscription</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <form method="post">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Matricule</label>
                    <input required type="text" name="matricule" placeholder="Matricule" autocomplete="off" maxlength="15"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Nom</label>
                    <input required type="text" name="nom" placeholder="Le nom svp" autocomplete="off" maxlength="100"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Prénoms</label>
                    <input required type="text" name="prenoms" placeholder="Vos prénoms svp" autocomplete="off" maxlength="100"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Date d'embauche</label>
                    <input required type="date"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Date de naissance</label>
                    <input required type="date"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Situation familiale</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="0">Célibataire</option>
                        <option value="1">Marié(e)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Nombre d'enfants</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @for($n = 1; $n <= 10; $n++)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Nationalité</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Téléphone</label>
                    <input type="text" name="tel" placeholder="Le numéro de téléphone svp" autocomplete="off" maxlength="8"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Département</label>
                    <select id="iddepartement" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Service (Équipe)</label>
                    <select id="idequipe" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Niveau d'étude</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Diplôme obtenu</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-text-primary mb-2">Un mot sur l'embauché</label>
                    <textarea id="mot" name="mot" rows="5"
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    Enregistrer les informations
                </button>
            </div>
        </form>
    </div>

@endsection
