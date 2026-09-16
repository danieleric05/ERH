@extends('layouts.erh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/select2/select2.css') }}">
@endpush

@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des variables (manuelle)</h1>
            <a href="{{ route('listevariables_manuelle') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
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
            <span class="text-text-primary font-medium">Ajouter une variable (manuelle)</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Informations de Variable</h2>

        <form action="{{ url('post_variables_manuelle') }}" method="POST" class="form-auth-small">
            @csrf

            <!-- Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Type Variable -->
                <div>
                    <label for="type_variable" class="block text-sm font-medium text-text-primary mb-2">Choisir le type</label>
                    <select name="type_variable" id="type_variable" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        <option value="1">DIMANCHE</option>
                        <option value="2">FERIE</option>
                        <option value="3">JOUR OUVRABLE</option>
                    </select>
                </div>

                <!-- Employés -->
                <div>
                    <label for="travailleurid" class="block text-sm font-medium text-text-primary mb-2">Employé(s)</label>
                    <select multiple name="travailleurid[]" id="travailleurid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->matricule }}">{{ $trav->nom }} {{ $trav->prenom }} {{ $trav->matricule }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cas Variables -->
                <div>
                    <label for="cas_variables" class="block text-sm font-medium text-text-primary mb-2">Cas</label>
                    <select name="cas_variables" id="cas_variables" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        <option value="1">RETARD D'ENROLEMENT</option>
                        <option value="2">DEFAUT DE POINTAGE</option>
                        <option value="3">OUBLI DE POINTAGE</option>
                        <option value="4">DEFAUT D'EMPREINTE</option>
                    </select>
                </div>

                <!-- Date debut -->
                <div>
                    <label for="debut" class="block text-sm font-medium text-text-primary mb-2">Date debut</label>
                    <input name="debut" id="debut" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white text-text-primary">
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="fin" class="block text-sm font-medium text-text-primary mb-2">Date de fin</label>
                    <input name="fin" id="fin" value="{{ date('Y-m-d') }}" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white text-text-primary">
                </div>

                <!-- Periode -->
                <div>
                    <label for="periode" class="block text-sm font-medium text-text-primary mb-2">Periode</label>
                    <select name="periode" id="periode" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white show-tick ms select2" data-placeholder="Select">
                        <option value="">-DEROULER-</option>
                        <option value="1">NUIT</option>
                        <option value="2">JOUR</option>
                    </select>
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
                    <a href="{{ route('listevariables_manuelle') }}">
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
