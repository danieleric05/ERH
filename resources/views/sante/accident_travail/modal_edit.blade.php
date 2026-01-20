<!-- Alpine.js Modal for Editing Accident Travail -->
<div x-data="modalEditAccident()" @open-modal-edit-accident.window="openModal($event)" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" @click.stop>
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Modifier l'Accident de Travail</h3>
            <button @click="closeModal()" class="text-text-secondary hover:text-text-primary text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form action="{{ url('update/accident_travail/updateaccident') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id" x-model="selectedId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Travailleur (Read-only) -->
                <div>
                    <label for="travailleur_name" class="block text-sm font-medium text-text-primary mb-2">Travailleur</label>
                    <input readonly type="text" id="travailleur_name"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-text-secondary"
                        x-model="travailleurName">
                </div>

                <!-- Date d'Accident -->
                <div>
                    <label for="dateconsul" class="block text-sm font-medium text-text-primary mb-2">Date de l'Accident</label>
                    <input type="date" name="dateconsul" id="dateconsul"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        x-model="dateconsul">
                </div>
            </div>

            <!-- Textareas Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Cause Details -->
                <div>
                    <label for="cause" class="block text-sm font-medium text-text-primary mb-2">Cause Détaillée</label>
                    <textarea name="cause" id="cause" rows="4"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        x-model="cause"></textarea>
                </div>

                <!-- Prescription -->
                <div>
                    <label for="prescription" class="block text-sm font-medium text-text-primary mb-2">Prescription</label>
                    <textarea name="prescription" id="prescription" rows="4"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        x-model="prescription"></textarea>
                </div>
            </div>

            <!-- Work Stoppage Section -->
            <div class="border-t border-slate-200 pt-6 mb-6">
                <h4 class="text-md font-semibold text-text-primary mb-4">Arrêt de Travail</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Arrêt Travail -->
                    <div>
                        <label for="arret_travail" class="block text-sm font-medium text-text-primary mb-2">Arrêt de Travail</label>
                        <select name="arret_travail" id="arret_travail" @change="updateArretFields()"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                            x-model="arretTravail">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>

                    <!-- Cause Arrêt (Conditional) -->
                    <div x-show="showCauseArret" class="hidden">
                        <label for="cause_arret_travail" class="block text-sm font-medium text-text-primary mb-2">Cause de l'Arrêt</label>
                        <select name="cause_arret_travail" id="cause_arret_travail"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                            x-model="causeArretTravail">
                            <option value="">-SÉLECTIONNER-</option>
                            <option value="Maladie Professionnelle">Maladie Professionnelle</option>
                            <option value="Accident de travail">Accident de travail</option>
                            <option value="Maladie">Maladie</option>
                            <option value="Autres">Autres</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Date Range (Conditional) -->
            <div x-show="showDateRange" class="hidden mb-6">
                <label class="block text-sm font-medium text-text-primary mb-2">Période d'Arrêt</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="debut_arret" class="block text-xs text-text-secondary mb-2">Début</label>
                        <input type="date" name="debut_arret" id="debut_arret"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            x-model="debutArret">
                    </div>
                    <div>
                        <label for="fin_arret" class="block text-xs text-text-secondary mb-2">Fin</label>
                        <input type="date" name="fin_arret" id="fin_arret"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            x-model="finArret">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                <button type="button" @click="closeModal()"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    Fermer
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function modalEditAccident() {
    return {
        isOpen: false,
        selectedId: null,
        travailleurName: '',
        dateconsul: '',
        cause: '',
        prescription: '',
        arretTravail: '',
        causeArretTravail: '',
        debutArret: '',
        finArret: '',
        showCauseArret: false,
        showDateRange: false,

        openModal(event) {
            this.isOpen = true;
            const detail = event.detail || {};
            this.selectedId = detail.id || null;
            this.travailleurName = detail.travailleur || '';
            this.dateconsul = detail.dateconsul || '';
            this.cause = detail.cause || '';
            this.prescription = detail.prescription || '';
            this.arretTravail = detail.arretTravail ? String(detail.arretTravail) : '';
            this.causeArretTravail = detail.causeArretTravail || '';
            this.debutArret = detail.debutArret || '';
            this.finArret = detail.finArret || '';
            this.updateArretFields();
        },

        closeModal() {
            this.isOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.selectedId = null;
            this.travailleurName = '';
            this.dateconsul = '';
            this.cause = '';
            this.prescription = '';
            this.arretTravail = '';
            this.causeArretTravail = '';
            this.debutArret = '';
            this.finArret = '';
            this.showCauseArret = false;
            this.showDateRange = false;
        },

        updateArretFields() {
            this.showCauseArret = this.arretTravail === '1';
            this.showDateRange = this.arretTravail === '1';
        }
    }
}

// Global function to trigger modal
window.openEditAccidentModal = function(data) {
    document.dispatchEvent(new CustomEvent('open-modal-edit-accident', { detail: data }));
}
</script>
