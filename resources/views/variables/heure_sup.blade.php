@extends('layouts.erh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/select2/select2.css') }}">
    <style>
        /* Uniformise l'apparence : ajoute la flèche de dropdown sur les multi-select,
           absente par défaut du thème select2 (contrairement aux select simples). */
        .select2-container-multi .select2-choices {
            position: relative;
            padding-right: 20px;
        }
        .select2-container-multi .select2-choices::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 18px;
            border-left: 1px solid #aaa;
            border-radius: 0 4px 4px 0;
            background-color: #ccc;
            background-image: url('{{ asset('rhassets/vendor/select2/select2.png') }}'), linear-gradient(to top, #ccc 0%, #eee 60%);
            background-repeat: no-repeat;
            background-position: 0 center, 0 0;
            pointer-events: none;
        }
    </style>
@endpush

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

        <form action="{{ url('post_variables_heure_sup') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Employés -->
                <div>
                    <label for="employer_hs" class="block text-sm font-medium text-text-primary mb-2">Employé(s)</label>
                    <select multiple name="employer_hs[]" id="employer_hs" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="-DEROULER-">
                        <option value=""></option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} {{ $trav->matricule }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type H.S -->
                <div>
                    <label for="heure_hs" class="block text-sm font-medium text-text-primary mb-2">Type H.S</label>
                    <select name="heure_hs" id="heure_hs" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="-DEROULER-">
                        <option value=""></option>
                        <option value="HS 15">HS 15</option>
                        <option value="HS 50">HS 50</option>
                        <option value="HS 75">HS 75</option>
                        <option value="HS 100">HS 100</option>
                    </select>
                </div>

                <!-- Nombre d'heure -->
                <div>
                    <label for="nbre_heure_hs" class="block text-sm font-medium text-text-primary mb-2">Nombre d'heure</label>
                    <select name="nbre_heure_hs" id="nbre_heure_hs" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="-DEROULER-">
                        <option value=""></option>
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
                    <input name="date_heure_supp" id="date_heure_supp" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white text-text-primary">
                </div>
            </div>

            <!-- Textarea -->
            <div class="mb-8">
                <label for="justification" class="block text-sm font-medium text-text-primary mb-2">Justification</label>
                <textarea rows="3" name="justification" id="justification" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white text-text-primary"></textarea>
            </div>

            <!-- Button Group -->
            <div class="border-t border-slate-200 pt-6">
                <div class="flex justify-center gap-4">
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Enregistrer
                    </button>
                    <a href="{{ route('listevariables_heure_supp') }}">
                        <button type="button" class="px-8 py-3 bg-slate-200 hover:bg-slate-300 text-text-primary font-medium rounded-lg transition">
                            Liste
                        </button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('rhassets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/select2/select2.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2({
                width: '100%',
                allowClear: true
            });
        });
    </script>
@endpush
