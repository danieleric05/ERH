@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter une Autorisation</h1>
            <a href="{{ route('listeautorisations') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Gestion RH</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter une autorisation</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Formulaire d'Autorisation</h2>

        <form action="{{ url('post_autorisation') }}" method="POST" x-data="initializeAutorisationForm()">
            @csrf

            <!-- Section 1: Informations Demandeur -->
            <div class="border-b border-slate-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Informations du Demandeur</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Demandeur -->
                    <div>
                        <label for="demandeurid" class="block text-sm font-medium text-text-primary mb-2">Demandeur</label>
                        <select required name="demandeurid" id="demandeurid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($data_travailleur as $trav)
                                <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-text-primary mb-2">Statut</label>
                        <select required name="statut" id="statut"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Directeur</option>
                            <option value="2">Cadre</option>
                            <option value="3">Agent de Maîtrise</option>
                            <option value="4">Ouvrier</option>
                        </select>
                    </div>

                    <!-- Motif de l'Absence -->
                    <div>
                        <label for="motif_absence" class="block text-sm font-medium text-text-primary mb-2">Motif de l'Absence</label>
                        <select required name="motif_absence" id="motif_absence" @change="updateConditionalFields()"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                            x-model="motifAbsence">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Maladie</option>
                            <option value="2">Convenance Personnelle</option>
                            <option value="3">Permissions Exceptionnelles</option>
                            <option value="4">Congés Payés</option>
                            <option value="5">Congés Sans Solde</option>
                            <option value="6">Mission</option>
                            <option value="7">Autres Cas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Dates et Horaires -->
            <div class="border-b border-slate-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Dates et Horaires</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Date Début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-text-primary mb-2">Date Début</label>
                        <input required type="date" name="date_debut" id="date_debut"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <!-- Date Fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-text-primary mb-2">Date Fin</label>
                        <input required type="date" name="date_fin" id="date_fin"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <!-- Date Reprise -->
                    <div>
                        <label for="date_reprise" class="block text-sm font-medium text-text-primary mb-2">Date Reprise</label>
                        <input required type="date" name="date_reprise" id="date_reprise"
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                    </div>

                    <!-- Samedi/Dimanche -->
                    <div>
                        <label for="weekend" class="block text-sm font-medium text-text-primary mb-2">Samedi/Dimanche</label>
                        <select required name="weekend" id="weekend"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Oui</option>
                            <option value="2">Non</option>
                        </select>
                    </div>
                </div>

                <!-- Heures (Conditional - Hours for specific reasons) -->
                <div x-show="showHours" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 pt-4 border-t border-slate-200">
                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-text-primary mb-2">Heure Début</label>
                        <input type="time" name="heure_debut" id="heure_debut"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="HH:MM">
                    </div>
                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-text-primary mb-2">Heure Fin</label>
                        <input type="time" name="heure_fin" id="heure_fin"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="HH:MM">
                    </div>
                </div>
            </div>

            <!-- Section 3: Gestion et Observations -->
            <div class="border-b border-slate-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Gestion et Observations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Intérim Assuré Par -->
                    <div>
                        <label for="interim_assurer_par" class="block text-sm font-medium text-text-primary mb-2">Intérim Assuré Par</label>
                        <select name="interim_assurer_par" id="interim_assurer_par"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($data_travailleur as $trav)
                                <option value="{{ $trav->id }}">{{ $trav->nom }} {{ $trav->prenom }} ({{ $trav->matricule }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Commentaire -->
                <div class="mt-4">
                    <label for="commentaire" class="block text-sm font-medium text-text-primary mb-2">Commentaire</label>
                    <textarea name="commentaire" id="commentaire" rows="4"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        placeholder="Observations et remarques..."></textarea>
                </div>
            </div>

            <!-- Section 4: Détails Mission (Conditional) -->
            <div x-show="showMissionSection" class="hidden border-t border-slate-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Détails de la Mission</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Continent -->
                    <div>
                        <label for="continent" class="block text-sm font-medium text-text-primary mb-2">Continent</label>
                        <select name="continent" id="continent"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Afrique</option>
                            <option value="2">Asie</option>
                            <option value="3">Amérique</option>
                            <option value="4">Europe</option>
                            <option value="5">Australie</option>
                        </select>
                    </div>

                    <!-- Pays -->
                    <div>
                        <label for="pays" class="block text-sm font-medium text-text-primary mb-2">Pays</label>
                        <input type="text" name="pays" id="pays"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="Nom du pays">
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-text-primary mb-2">Ville</label>
                        <input type="text" name="ville" id="ville"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="Nom de la ville">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Mode de Transport -->
                    <div>
                        <label for="mode_transport" class="block text-sm font-medium text-text-primary mb-2">Mode de Transport</label>
                        <select name="mode_transport" id="mode_transport"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Avion</option>
                            <option value="2">Bateau</option>
                            <option value="3">Train</option>
                            <option value="4">Véhicule</option>
                        </select>
                    </div>

                    <!-- Divers (Allowance) -->
                    <div>
                        <label for="divers" class="block text-sm font-medium text-text-primary mb-2">Indemnité Divers</label>
                        <select name="divers" id="divers"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="5000">5 000 FCFA</option>
                            <option value="10000">10 000 FCFA</option>
                            <option value="15000">15 000 FCFA</option>
                            <option value="20000">20 000 FCFA</option>
                            <option value="25000">25 000 FCFA</option>
                            <option value="30000">30 000 FCFA</option>
                            <option value="40000">40 000 FCFA</option>
                            <option value="50000">50 000 FCFA</option>
                            <option value="75000">75 000 FCFA</option>
                            <option value="100000">100 000 FCFA</option>
                        </select>
                    </div>

                    <!-- Achat Mission -->
                    <div>
                        <label for="achat_mission" class="block text-sm font-medium text-text-primary mb-2">Achat Mission</label>
                        <input type="text" name="achat_mission" id="achat_mission"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="Montant ou détails">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Nuitée (Read-only calculated field) -->
                    <div>
                        <label for="nuitee" class="block text-sm font-medium text-text-primary mb-2">Nuitée(s)</label>
                        <input readonly type="text" name="nuitee" id="nuitee"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-text-secondary font-semibold"
                            placeholder="Calculé automatiquement">
                    </div>

                    <!-- Journée (Read-only calculated field) -->
                    <div>
                        <label for="journee" class="block text-sm font-medium text-text-primary mb-2">Journée(s)</label>
                        <input readonly type="text" name="journee" id="journee"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-text-secondary font-semibold"
                            placeholder="Calculé automatiquement">
                    </div>

                    <!-- Participation Événement -->
                    <div>
                        <label for="part_eve" class="block text-sm font-medium text-text-primary mb-2">Participation Événement</label>
                        <input type="text" name="part_eve" id="part_eve"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            placeholder="Nom de l'événement">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-center gap-4 pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fa fa-save mr-2"></i>Enregistrer
                </button>
                <a href="{{ route('listeautorisations') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    <i class="fa fa-list mr-2"></i>Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function initializeAutorisationForm() {
    return {
        motifAbsence: '',
        showHours: false,
        showMissionSection: false,

        updateConditionalFields() {
            const motif = document.getElementById('motif_absence').value;

            // Show hours for specific motifs (values 1, 2, 3, 7 = Maladie, Convenance, Permissions, Autres)
            this.showHours = ['1', '2', '3', '7'].includes(motif);

            // Show mission section only for MISSION (value 6)
            this.showMissionSection = motif === '6';
        }
    }
}
</script>

@endsection
