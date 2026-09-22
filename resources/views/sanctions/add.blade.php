@extends('layouts.erh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/select2/select2.css') }}">
@endpush

@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter une Sanction</h1>
            <a href="{{ route('listesanctions') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <a href="{{ route('listesanctions') }}" class="hover:text-primary-accent">Sanctions</a>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter une sanction</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Formulaire de Sanction</h2>

        <form action="{{ url('post_sanction') }}" method="POST" x-data="initializeSanctionForm()">
            @csrf

            <!-- First Row: Demandeur, Employé(s), Motif -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Demandeur -->
                <div>
                    <label for="demandeurid" class="block text-sm font-medium text-text-primary mb-2">Demandeur</label>
                    <select required name="demandeurid" id="demandeurid" data-placeholder="-SÉLECTIONNER-">
                        <option value=""></option>
                        @foreach($data_travailleur as $trav)
                            <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Employé(s) -->
                <div>
                    <label for="concerneid" class="block text-sm font-medium text-text-primary mb-2">Employé(s)</label>
                    <select required name="concerneid[]" id="concerneid" multiple data-placeholder="-SÉLECTIONNER-">
                        @foreach($data_travailleurJour as $trav)
                            <option value="{{ $trav->matricule }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Motif -->
                <div>
                    <label for="motif" class="block text-sm font-medium text-text-primary mb-2">Motif</label>
                    <select required name="motif" id="motif"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">Absence Injustifiée</option>
                        <option value="2">Insubordination</option>
                        <option value="3">Retard Répétitif</option>
                        <option value="4">Faute Lourde</option>
                        <option value="5">Insuffisance de Rendement</option>
                        <option value="6">Négligence Professionnelle</option>
                        <option value="7">S'endormir au poste de travail</option>
                    </select>
                </div>
            </div>

            <!-- Exposé des Motifs -->
            <div class="mb-8">
                <label for="expose_motif" class="block text-sm font-medium text-text-primary mb-2">Exposé des Motifs</label>
                <textarea name="expose_motif" id="expose_motif" rows="4"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Décrivez les raisons et circonstances de la sanction..."></textarea>
            </div>

            <!-- Sanction Details -->
            <div class="border-t border-slate-200 pt-6 mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Détails de la Sanction</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Sanction Appliquée -->
                    <div>
                        <label for="sanction_applique" class="block text-sm font-medium text-text-primary mb-2">Sanction Appliquée</label>
                        <select required name="sanction_applique" id="sanction_applique" @change="updateSanctionFields()"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                            x-model="sanctionApplique">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Avertissement</option>
                            <option value="2">Mise à Pied</option>
                            <option value="3">Licenciement</option>
                        </select>
                    </div>

                    <!-- Date de la Faute -->
                    <div>
                        <label for="datefautes" class="block text-sm font-medium text-text-primary mb-2">Date de la Faute</label>
                        <input required type="date" name="datefautes" id="datefautes"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <!-- Date de la Sanction -->
                    <div>
                        <label for="datesanction" class="block text-sm font-medium text-text-primary mb-2">Date de la Sanction</label>
                        <input required type="date" name="datesanction" id="datesanction"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <!-- Quart Concerné -->
                    <div>
                        <label for="quarts" class="block text-sm font-medium text-text-primary mb-2">Quart Concerné</label>
                        <select name="quarts" id="quarts"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Quart 1</option>
                            <option value="2">Quart 2</option>
                            <option value="3">Quart 3</option>
                        </select>
                    </div>
                </div>

                <!-- Conditional Fields for Mise à Pied -->
                <div x-show="showMiseAPiedFields" class="border-t border-slate-200 pt-4 mt-4">
                    <h4 class="text-md font-semibold text-text-primary mb-4">Période de Mise à Pied</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Nombre de Jours -->
                        <div>
                            <label for="nombre_jour" class="block text-sm font-medium text-text-primary mb-2">Nombre de Jours</label>
                            <select name="nombre_jour" id="nombre_jour"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                                <option value="">-SÉLECTIONNER-</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!-- Début -->
                        <div>
                            <label for="debut" class="block text-sm font-medium text-text-primary mb-2">Début</label>
                            <input type="date" name="debut" id="debut"
                                value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                        </div>

                        <!-- Fin -->
                        <div>
                            <label for="fin" class="block text-sm font-medium text-text-primary mb-2">Fin</label>
                            <input type="date" name="fin" id="fin"
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
                <a href="{{ route('listesanctions') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    <i class="fa fa-list mr-2"></i>Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function initializeSanctionForm() {
    return {
        sanctionApplique: '',
        showMiseAPiedFields: false,

        updateSanctionFields() {
            const sanctionValue = document.getElementById('sanction_applique').value;
            // Show additional fields only for "Mise à Pied" (value 2)
            this.showMiseAPiedFields = sanctionValue === '2';
        }
    }
}
</script>

@push('scripts')
    <script src="{{ asset('rhassets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/select2/select2.min.js') }}"></script>
    <script>
        $(function () {
            $('#demandeurid').select2({
                width: '100%',
                allowClear: true
            });
            $('#concerneid').select2({
                width: '100%'
            });
        });
    </script>
@endpush

@endsection
