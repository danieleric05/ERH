@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des tenues</h1>
            <a href="{{ route('listetenues') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
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
            <span class="text-text-primary font-medium">Attribuer une tenue</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Informations de Tenue</h2>

        <form action="{{ url('post_gestion_tenue') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Date -->
                <div>
                    <label for="datereception" class="block text-sm font-medium text-text-primary mb-2">Date</label>
                    <input name="datereception" id="datereception" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Travailleur -->
                <div>
                    <label for="travailleurid" class="block text-sm font-medium text-text-primary mb-2">Travailleur</label>
                    <select name="travailleurid" id="travailleurid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} {{ $trav->matricule }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Services -->
                <div>
                    <label for="services" class="block text-sm font-medium text-text-primary mb-2">Services</label>
                    <select required name="services" id="services" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_Services as $data)
                            <option value="{{ $data->id }}">{{ $data->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Article reçu -->
                <div>
                    <label for="tenuerecu" class="block text-sm font-medium text-text-primary mb-2">Article reçu</label>
                    <select required name="tenuerecu" id="tenuerecu" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_ArticleRecu as $data)
                             <option value="{{ $data->id }}">{{ $data->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ancienne ou Nouvelle -->
                <div>
                    <label for="etat" class="block text-sm font-medium text-text-primary mb-2">Ancienne ou Nouvelle</label>
                    <select required name="etat" id="etat" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        <option selected value="1">Nouvelle</option>
                        <option value="4">Ancienne</option>
                    </select>
                </div>
            </div>

            <!-- Textareas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Détail de la tenue -->
                <div>
                    <label for="detail_tenue" class="block text-sm font-medium text-text-primary mb-2">Détail de la tenue</label>
                    <textarea name="detail_tenue" id="detail_tenue" placeholder="Couleur, Taille, Défaut" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>

                <!-- Détail de la chaussure -->
                <div>
                    <label for="detail_chaussure" class="block text-sm font-medium text-text-primary mb-2">Détail de la chaussure</label>
                    <textarea name="detail_chaussure" id="detail_chaussure" placeholder="Pointure, Couleur, etc" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Enregistrer
                </button>
                <a href="{{ route('listetenues') }}" class="px-8 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                    Liste
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
