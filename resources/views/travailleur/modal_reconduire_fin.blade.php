<!-- Alpine.js Modal pour Reconduction Journalier (Fin) -->
<div x-data="modalReconduireFin()" @open-modal-reconduire-fin.window="openModal()" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" @click.stop>
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Action sur un journalier</h3>
            <button @click="closeModal()" class="text-text-secondary hover:text-text-primary text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form action="{{ url('update/reconduire/uptravailleur') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id" x-model="selectedId">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Action Selection -->
                <div>
                    <label for="actionid_recfin" class="block text-sm font-medium text-text-primary mb-2">Effectuer</label>
                    <select name="actionid" id="actionid_recfin" x-model="selectedAction"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                        required>
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="6" selected>Reconduire un journalier</option>
                    </select>
                </div>

                <!-- Date de reprise -->
                <div>
                    <label for="datechoisit_recfin" class="block text-sm font-medium text-text-primary mb-2">Date de reprise</label>
                    <input type="date" name="datechoisit" id="datechoisit_recfin"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        required>
                </div>

                <!-- Date fin de contrat -->
                <div>
                    <label for="datefin_recfin" class="block text-sm font-medium text-text-primary mb-2">Date fin de contrat</label>
                    <input type="date" name="datefin" id="datefin_recfin"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        required>
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
function modalReconduireFin() {
    return {
        isOpen: false,
        selectedId: null,
        selectedAction: '6',

        openModal(id = null) {
            this.isOpen = true;
            this.selectedId = id;
            this.selectedAction = '6';
        },

        closeModal() {
            this.isOpen = false;
            this.selectedId = null;
            this.selectedAction = '6';
        }
    }
}

// Global function to trigger modal
window.openRecondmireFinModal = function(id) {
    window.dispatchEvent(new CustomEvent('open-modal-reconduire-fin', { detail: { id: id } }));
}
</script>