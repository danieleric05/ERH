@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter un Accident de Travail</h1>
            <a href="{{ route('listesconsultation') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Santé</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter un accident de travail</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Informations d'Accident de Travail</h2>

        <form action="{{ url('post_accident_travail') }}" method="POST" x-data="initializeAccidentForm()">
            @csrf

            <!-- Main Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Travailleur -->
                <div>
                    <label for="travailleurid" class="block text-sm font-medium text-text-primary mb-2">Travailleur</label>
                    <select required name="travailleurid" id="travailleurid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date d'Accident -->
                <div>
                    <label for="dateconsul" class="block text-sm font-medium text-text-primary mb-2">Date de l'Accident</label>
                    <input required type="date" name="dateconsul" id="dateconsul"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>

            <!-- Textareas Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Cause Details -->
                <div>
                    <label for="cause" class="block text-sm font-medium text-text-primary mb-2">Cause Détaillée de l'Accident</label>
                    <textarea name="cause" id="cause" rows="5"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        placeholder="Décrivez détaillement les circonstances de l'accident..."></textarea>
                </div>

                <!-- Prescription -->
                <div>
                    <label for="prescription" class="block text-sm font-medium text-text-primary mb-2">Prescription</label>
                    <textarea name="prescription" id="prescription" rows="5"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        placeholder="Prescriptions médicales..."></textarea>
                </div>
            </div>

            <!-- Work Stoppage Section -->
            <div class="border-t border-slate-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Arrêt de Travail</h3>

                <!-- Arrêt Travail Selection -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="arret_travail" class="block text-sm font-medium text-text-primary mb-2">Arrêt de Travail</label>
                        <select name="arret_travail" id="arret_travail" @change="updateArretFields()"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>

                    <!-- Cause Arrêt (Conditional) -->
                    <div x-show="showCauseArret" class="hidden">
                        <label for="cause_arret_travail" class="block text-sm font-medium text-text-primary mb-2">Cause de l'Arrêt</label>
                        <select name="cause_arret_travail" id="cause_arret_travail"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="Maladie Professionnelle">Maladie Professionnelle</option>
                            <option selected value="Accident de travail">Accident de travail</option>
                            <option value="Maladie">Maladie</option>
                            <option value="Autres">Autres</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range (Conditional) -->
                <div x-show="showDateRange" class="hidden">
                    <label class="block text-sm font-medium text-text-primary mb-2">Période d'Arrêt</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="debut_arret" class="block text-xs text-text-secondary mb-2">Début</label>
                            <input type="date" name="debut_arret" id="debut_arret"
                                value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>
                        <div>
                            <label for="fin_arret" class="block text-xs text-text-secondary mb-2">Fin</label>
                            <input type="date" name="fin_arret" id="fin_arret"
                                value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-center gap-4 pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fa fa-save mr-2"></i>Enregistrer
                </button>
                <a href="{{ route('listesconsultation') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    <i class="fa fa-list mr-2"></i>Accidents
                </a>
                <a href="{{ route('detect_matricul') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    <i class="fa fa-users mr-2"></i>Travailleurs
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function initializeAccidentForm() {
    return {
        showCauseArret: false,
        showDateRange: false,

        updateArretFields() {
            const arretValue = document.getElementById('arret_travail').value;
            this.showCauseArret = arretValue === '1';
            this.showDateRange = arretValue === '1';
        }
    }
}
</script>

@endsection
