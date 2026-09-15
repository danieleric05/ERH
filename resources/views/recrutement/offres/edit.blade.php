@extends('layouts.erh')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Recrutement</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Offres d'Emploi</a></li>
                    <li class="breadcrumb-item">Modifier Offre</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-12">
            @include('errors')

            <div class="card">
                <div class="header">
                    <h2>Modifier Offre d'Emploi</h2>
                </div>

                <div class="body">
                    <form action="{{ route('offres.update', $offre->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="titre" class="block text-sm font-medium text-text-primary mb-2">Titre <span class="text-red-500">*</span></label>
                            <input type="text" id="titre" name="titre" required
                                   value="{{ old('titre', $offre->titre) }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>

                        <!-- Row 1: Departement, Fonction, Unité -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="departement_id" class="block text-sm font-medium text-text-primary mb-2">Département</label>
                                <select id="departement_id" name="departement_id"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                                    <option value="">- Sélectionner -</option>
                                    @foreach($departements as $dept)
                                    <option value="{{ $dept->id }}" {{ old('departement_id', $offre->departement_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="fonction_id" class="block text-sm font-medium text-text-primary mb-2">Fonction</label>
                                <select id="fonction_id" name="fonction_id"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                                    <option value="">- Sélectionner -</option>
                                    @foreach($fonctions as $fonc)
                                    <option value="{{ $fonc->id }}" {{ old('fonction_id', $offre->fonction_id) == $fonc->id ? 'selected' : '' }}>
                                        {{ $fonc->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="unite_id" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                                <select id="unite_id" name="unite_id"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                                    <option value="">- Sélectionner -</option>
                                    @foreach($unites as $unite)
                                    <option value="{{ $unite->id }}" {{ old('unite_id', $offre->unite_id) == $unite->id ? 'selected' : '' }}>
                                        {{ $unite->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Type Contrat, Nombre de Postes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="type_contrat" class="block text-sm font-medium text-text-primary mb-2">Type de Contrat <span class="text-red-500">*</span></label>
                                <select id="type_contrat" name="type_contrat" required
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                                    <option value="CDI" {{ old('type_contrat', $offre->type_contrat) === 'CDI' ? 'selected' : '' }}>CDI</option>
                                    <option value="CDD" {{ old('type_contrat', $offre->type_contrat) === 'CDD' ? 'selected' : '' }}>CDD</option>
                                    <option value="Journalier" {{ old('type_contrat', $offre->type_contrat) === 'Journalier' ? 'selected' : '' }}>Journalier</option>
                                </select>
                            </div>

                            <div>
                                <label for="nombre_postes" class="block text-sm font-medium text-text-primary mb-2">Nombre de Postes <span class="text-red-500">*</span></label>
                                <input type="number" id="nombre_postes" name="nombre_postes" required min="1"
                                       value="{{ old('nombre_postes', $offre->nombre_postes) }}"
                                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-text-primary mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" required rows="5"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">{{ old('description', $offre->description) }}</textarea>
                        </div>

                        <!-- Compétences Requises -->
                        <div class="mb-6">
                            <label for="competences_requises" class="block text-sm font-medium text-text-primary mb-2">Compétences Requises</label>
                            <textarea id="competences_requises" name="competences_requises" rows="3"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">{{ old('competences_requises', $offre->competences_requises) }}</textarea>
                        </div>

                        <!-- Expérience Requise -->
                        <div class="mb-6">
                            <label for="experience_requise" class="block text-sm font-medium text-text-primary mb-2">Expérience Requise</label>
                            <input type="text" id="experience_requise" name="experience_requise"
                                   value="{{ old('experience_requise', $offre->experience_requise) }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>

                        <!-- Salaires -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="salaire_min" class="block text-sm font-medium text-text-primary mb-2">Salaire Minimum</label>
                                <input type="number" id="salaire_min" name="salaire_min" step="0.01"
                                       value="{{ old('salaire_min', $offre->salaire_min) }}"
                                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                            </div>

                            <div>
                                <label for="salaire_max" class="block text-sm font-medium text-text-primary mb-2">Salaire Maximum</label>
                                <input type="number" id="salaire_max" name="salaire_max" step="0.01"
                                       value="{{ old('salaire_max', $offre->salaire_max) }}"
                                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                            </div>
                        </div>

                        <!-- Date Cloture -->
                        <div class="mb-6">
                            <label for="date_cloture" class="block text-sm font-medium text-text-primary mb-2">Date de Clôture</label>
                            <input type="date" id="date_cloture" name="date_cloture"
                                   value="{{ old('date_cloture', $offre->date_cloture?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-center gap-4 pt-6 border-t border-slate-200">
                            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <i class="fa fa-save mr-2"></i>Mettre à Jour
                            </button>
                            <a href="{{ route('offres.index') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                                <i class="fa fa-times mr-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
