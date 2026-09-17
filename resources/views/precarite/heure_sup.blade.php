@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des variables (heure supplémentaire)</h1>
            <a href="{{ route('listevariables_heure_supp') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Configuration</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter une variable (heure supplémentaire)</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Informations de Variable</h2>

        <form action="{{ url('post_variables') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Employé(s) -->
                <div>
                    <label for="travailleurid" class="block text-sm font-medium text-text-primary mb-2">Employé(s)</label>
                    <select name="travailleurid" id="travailleurid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} {{ $trav->matricule }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre d'heure -->
                <div>
                    <label for="motif" class="block text-sm font-medium text-text-primary mb-2">Nombre d'heure</label>
                    <select name="motif" id="motif" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        <option value="1">1 Heure</option>
                        <option value="2">2 Heures</option>
                        <option value="3">3 Heures</option>
                        <option value="4">4 Heures</option>
                        <option value="5">5 Heures</option>
                        <option value="6">6 Heures</option>
                        <option value="7">7 Heures</option>
                        <option value="8">8 Heures</option>
                        <option value="9">9 Heures</option>
                        <option value="10">10 Heures</option>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="date_heure_supp" class="block text-sm font-medium text-text-primary mb-2">Date</label>
                    <input name="date_heure_supp" id="date_heure_supp" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>

            <!-- Justification Textarea -->
            <div class="mb-8">
                <label for="justification" class="block text-sm font-medium text-text-primary mb-2">Justification</label>
                <textarea rows="3" name="justification" id="justification" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Enregistrer
                </button>
                <a href="{{ route('listevariables_heure_supp') }}" class="px-8 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Liste
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
