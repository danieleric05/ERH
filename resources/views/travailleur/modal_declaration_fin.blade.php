<!-- Alpine.js Modal pour Déclaration (Fin) -->
<div x-data="modalDeclarationFin()" @open-modal-declaration-fin.window="openModal()" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" @click.stop>
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Effectuer une action</h3>
            <button @click="closeModal()" class="text-text-secondary hover:text-text-primary text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form action="{{ url('update/declarations/updatedeclaration') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id" x-model="selectedId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Action Selection -->
                <div>
                    <label for="actionid_fin" class="block text-sm font-medium text-text-primary mb-2">Effectuer</label>
                    <select name="actionid" id="actionid_fin" x-model="selectedAction" @change="updateFields()"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                        required>
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="3">Cessation</option>
                        <option value="4">Certificat de Travail</option>
                        <option value="5">Déclaration CNPS</option>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="datechoisit_fin" class="block text-sm font-medium text-text-primary mb-2">Date</label>
                    <input type="date" name="datechoisit" id="datechoisit_fin"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        required>
                </div>
            </div>

            <!-- Motif de départ (visible si cessation) -->
            <div x-show="selectedAction === '3'" class="mb-6 hidden">
                <label for="motif_fin_contrat_fin" class="block text-sm font-medium text-text-primary mb-2">Motif de départ</label>
                <select id="motif_fin_contrat_fin" x-model="motifChoice"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                    <option value="">-SÉLECTIONNER-</option>
                    <option value="Démission">Démission</option>
                    <option value="Licenciement pour faute">Licenciement pour faute</option>
                    <option value="Licenciement économique">Licenciement économique</option>
                    <option value="Fin de contrat (CDD)">Fin de contrat (CDD)</option>
                    <option value="Rupture conventionnelle">Rupture conventionnelle</option>
                    <option value="Rupture période d'essai">Rupture période d'essai</option>
                    <option value="Retraite">Retraite</option>
                    <option value="Décès">Décès</option>
                    <option value="Abandon de poste">Abandon de poste</option>
                    <option value="autre">Autre (préciser)</option>
                </select>
                <input x-show="motifChoice === 'autre'" type="text" x-model="motifAutre"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent mt-3"
                    placeholder="Précisez le motif de départ">
                <input type="hidden" name="motif_fin_contrat" :value="motifChoice === 'autre' ? motifAutre : motifChoice">
            </div>

            <!-- Numéro CNPS (visible si déclaration CNPS) -->
            <div x-show="selectedAction === '5'" class="mb-6 hidden">
                <label for="numcnps_fin" class="block text-sm font-medium text-text-primary mb-2">Numéro CNPS</label>
                <input type="text" name="numcnps" id="numcnps_fin"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Numéro CNPS"
                    maxlength="12">
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
function modalDeclarationFin() {
    return {
        isOpen: false,
        selectedId: null,
        selectedAction: '',
        motifChoice: '',
        motifAutre: '',

        openModal(id = null) {
            this.isOpen = true;
            this.selectedId = id;
            this.selectedAction = '';
            this.motifChoice = '';
            this.motifAutre = '';
        },

        closeModal() {
            this.isOpen = false;
            this.selectedId = null;
            this.selectedAction = '';
            this.motifChoice = '';
            this.motifAutre = '';
        },

        updateFields() {
            // Show/hide fields based on selected action
        }
    }
}

// Global function to trigger modal
window.openDeclarationFinModal = function(id) {
    window.dispatchEvent(new CustomEvent('open-modal-declaration-fin', { detail: { id: id } }));
}
</script>
