@extends('layouts.erh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/select2/select2.css') }}">
@endpush

@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Ajouter un Travailleur</h1>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Recrutement</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter travailleur</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Étape 1 : Enregistrement du travailleur</h2>

        <form action="{{ url('post_travailleur') }}" method="POST"
              x-data="initializeForm(@js('J000' . (intval($maj_big) + 1)), @js('E0' . (intval($maj_big_e) + 1)))">
            @csrf

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- Matricule -->
                <div>
                    <label for="matricule" class="block text-sm font-medium text-text-primary mb-2">Matricule</label>
                    <input required type="text" name="matricule" id="matricule"
                        value="{{ old('matricule', 'J000' . (intval($maj_big) + 1)) }}"
                        placeholder="Le matricule"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                        maxlength="50">
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-text-primary mb-2">Nom</label>
                    <input required type="text" name="nom" id="nom"
                        placeholder="Nom"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase"
                        @input="$el.value = $el.value.toUpperCase()">
                    <input type="hidden" value="{{ $maj_big }}" name="matri_quatre">
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-text-primary mb-2">Prénoms</label>
                    <input required type="text" name="prenom" id="prenom"
                        placeholder="Prénoms"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase"
                        maxlength="150"
                        @input="$el.value = $el.value.toUpperCase()">
                </div>

                <!-- Date de naissance -->
                <div>
                    <label for="datenaissance" class="block text-sm font-medium text-text-primary mb-2">Date de naissance</label>
                    <input required type="date" name="datenaissance" id="datenaissance"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Situation matrimoniale -->
                <div>
                    <label for="situation_mat" class="block text-sm font-medium text-text-primary mb-2">Situation matrimoniale</label>
                    <select required name="situation_mat" id="situation_mat"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="Celibataire">Célibataire</option>
                        <option value="Marie">Marié(e)</option>
                    </select>
                </div>

                <!-- Unité -->
                <div>
                    <label for="uniteid" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                    <select required name="uniteid" id="uniteid" class="select2 w-full"
                        data-placeholder="-SÉLECTIONNER-">
                        <option value=""></option>
                        @foreach($data_unites as $unite)
                            <option value="{{ $unite->id }}">{{ $unite->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Département -->
                <div>
                    <label for="departementid" class="block text-sm font-medium text-text-primary mb-2">Département</label>
                    <select required name="departementid" id="departementid" class="select2 w-full"
                        data-placeholder="-SÉLECTIONNER-">
                        <option value=""></option>
                        @foreach($data_departements as $depart)
                            <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Équipe -->
                <div>
                    <label for="equipeid" class="block text-sm font-medium text-text-primary mb-2">Équipe</label>
                    <select required name="equipeid" id="equipeid" class="select2 w-full"
                        data-placeholder="-SÉLECTIONNER-">
                        <option value=""></option>
                        @foreach($data_equipes as $equipe)
                            <option value="{{ $equipe->id }}">{{ $equipe->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pays -->
                <div>
                    <label for="paysid" class="block text-sm font-medium text-text-primary mb-2">Pays</label>
                    <select required name="paysid" id="paysid" class="select2 w-full"
                        data-placeholder="-SÉLECTIONNER-">
                        <option value=""></option>
                        @foreach($data_pays as $pays)
                            <option value="{{ $pays->id }}">{{ $pays->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Contrat -->
                <div>
                    <label for="idtype_contrat" class="block text-sm font-medium text-text-primary mb-2">Type Contrat</label>
                    <select required name="idtype_contrat" id="idtype_contrat"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                        @change="handleContractTypeChange($event)">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_typecontrat as $contrat)
                            <option value="{{ $contrat->id }}">{{ $contrat->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Étape suivante -->
                <div>
                    <label for="etapeid" class="block text-sm font-medium text-text-primary mb-2">Étape suivante ?</label>
                    <select required name="etapeid" id="etapeid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>

                <!-- Date d'embauche CDI/CDD -->
                <div x-show="showEmbaucheContrat" class="hidden">
                    <label for="date_embauche_contrat_new" class="block text-sm font-medium text-text-primary mb-2">Date d'embauche</label>
                    <input type="date" name="date_embauche_contrat_new" id="date_embauche_contrat_new"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Date d'embauche Journalier -->
                <div x-show="showEmbaucheJournalier" class="hidden">
                    <label for="date_embauche_journalier" class="block text-sm font-medium text-text-primary mb-2">Date d'embauche</label>
                    <input type="date" value="{{ date('Y-m-d') }}" name="date_embauche_journalier" id="date_embauche_journalier"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

                <!-- Date fin contrat -->
                <div x-show="showFinContrat" class="hidden">
                    <label for="date_fin_contrat_new" class="block text-sm font-medium text-text-primary mb-2">Date de fin de contrat</label>
                    <input type="date" name="date_fin_contrat_new" id="date_fin_contrat_new"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>

            </div>

            <!-- Textarea - Un Mot Sur Le Journalier -->
            <div class="mb-8">
                <label for="mot" class="block text-sm font-medium text-text-primary mb-2">Un Mot Sur Le Journalier</label>
                <textarea name="mot" id="mot" rows="4"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Décrivez les compétences ou savoir-faire du journalier..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-4">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Enregistrer
                </button>
                <a href="{{ route('listetravailleurs') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function initializeForm(suggestionJournalier, suggestionEmbauche) {
    return {
        showEmbaucheContrat: false,
        showEmbaucheJournalier: false,
        showFinContrat: false,
        handleContractTypeChange(event) {
            const value = event.target.value;
            // Types réels (e_typecontrat) : 1=STAGE, 2=CDD, 3=CDI, 4=JOURNALIER
            this.showEmbaucheContrat = value === '2' || value === '3'; // CDD ou CDI
            this.showEmbaucheJournalier = value === '4'; // Journalier (était '1' par erreur, id réel du STAGE)
            this.showFinContrat = value !== '';

            const matricule = document.getElementById('matricule');
            if (this.showEmbaucheContrat) {
                matricule.value = suggestionEmbauche;
            } else if (this.showEmbaucheJournalier) {
                matricule.value = suggestionJournalier;
            }
        }
    }
}
</script>

@push('scripts')
    <script src="{{ asset('rhassets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/select2/select2.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2({ width: '100%', allowClear: true });
        });
    </script>
@endpush

@endsection