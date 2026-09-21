@extends('layouts.erh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/select2/select2.css') }}">
@endpush

@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter un Congé</h1>
            <a href="{{ route('listeconges') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <a href="{{ route('listeconges') }}" class="hover:text-primary-accent">Gestion des congés</a>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter un congé</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Formulaire de demande de congé</h2>

        <form action="{{ url('post_conges') }}" method="POST">
            @csrf

            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="travailleurid" class="block text-sm font-medium text-text-primary mb-2">Travailleur</label>
                        <select required name="travailleurid" id="travailleurid" data-placeholder="-SÉLECTIONNER-">
                            <option value=""></option>
                            @foreach($data_travailleur as $trav)
                                <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type_conge" class="block text-sm font-medium text-text-primary mb-2">Type de congé</label>
                        <select required name="type_conge" id="type_conge"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Congé annuel</option>
                            <option value="2">Congé maladie</option>
                            <option value="3">Congé maternité/paternité</option>
                            <option value="4">Congé sans solde</option>
                            <option value="5">Congé exceptionnel</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="debut" class="block text-sm font-medium text-text-primary mb-2">Date début</label>
                        <input required type="date" name="debut" id="debut" value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <div>
                        <label for="fin" class="block text-sm font-medium text-text-primary mb-2">Date de fin</label>
                        <input required type="date" name="fin" id="fin" value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <div>
                        <label for="date_reprise" class="block text-sm font-medium text-text-primary mb-2">Date de reprise</label>
                        <input required type="date" name="date_reprise" id="date_reprise" value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="justification" class="block text-sm font-medium text-text-primary mb-2">Justification</label>
                <textarea rows="3" name="justification" id="justification"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fa fa-check"></i> Enregistrer
                </button>
                <a href="{{ route('listeconges') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-200 text-text-primary rounded-lg hover:bg-slate-300 transition font-semibold">
                    <i class="fa fa-list"></i> Liste
                </a>
                <a href="{{ route('calendrier_conges') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition font-semibold">
                    <i class="fa fa-calendar"></i> Calendrier
                </a>
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
            $('#travailleurid').select2({
                width: '100%',
                allowClear: true
            });
        });
    </script>
@endpush
