<!-- Alpine.js Modal pour Déclaration -->
<div x-data="modalDeclaration()" @open-modal-declaration.window="openModal($event)" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
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
                    <label for="actionid" class="block text-sm font-medium text-text-primary mb-2">Effectuer</label>
                    <select name="actionid" id="actionid" x-model="selectedAction" @change="updateFields()"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                        required>
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="3">Cessation</option>
                        <option value="4">Certificat de Travail</option>
                        <option value="5">Déclaration CNPS</option>
                        <option value="8">Modifier Unité/Équipe</option>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="datechoisit" class="block text-sm font-medium text-text-primary mb-2">Date</label>
                    <input type="date" name="datechoisit" id="datechoisit"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                        required>
                </div>
            </div>

            <!-- Motif de départ (visible si cessation) -->
            <div x-show="selectedAction === '3'" class="mb-6 hidden">
                <label for="motif_fin_contrat" class="block text-sm font-medium text-text-primary mb-2">Motif de départ</label>
                <input type="text" name="motif_fin_contrat" id="motif_fin_contrat"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Motif de départ">
            </div>

            <!-- Numéro CNPS (visible si déclaration CNPS) -->
            <div x-show="selectedAction === '5'" class="mb-6 hidden">
                <label for="numcnps" class="block text-sm font-medium text-text-primary mb-2">Numéro CNPS</label>
                <input type="text" name="numcnps" id="numcnps"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Numéro CNPS"
                    maxlength="12">
            </div>

            <!-- Unité/Équipe (visible si modification) -->
            <div x-show="selectedAction === '8'" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Unité -->
                    <div>
                        <label for="idunite" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                        <select name="uniteid" id="idunite"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                        </select>
                    </div>

                    <!-- Département -->
                    <div>
                        <label for="departementid" class="block text-sm font-medium text-text-primary mb-2">Département</label>
                        <select name="departementid" id="departementid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                        </select>
                    </div>

                    <!-- Équipe -->
                    <div>
                        <label for="equipeid" class="block text-sm font-medium text-text-primary mb-2">Équipe</label>
                        <select name="equipeid" id="equipeid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                            <option value="">-SÉLECTIONNER-</option>
                        </select>
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
function modalDeclaration() {
    return {
        isOpen: false,
        selectedId: null,
        selectedAction: '',

        openModal(event) {
            this.isOpen = true;
            const detail = event?.detail || {};
            this.selectedId = detail.id || null;
            this.selectedAction = '';
        },

        closeModal() {
            this.isOpen = false;
            this.selectedId = null;
            this.selectedAction = '';
        },

        updateFields() {
            // Show/hide fields based on selected action
        }
    }
}

// Global function to trigger modal
window.openDeclarationModal = function(id) {
    document.dispatchEvent(new CustomEvent('open-modal-declaration', { detail: { id: id } }));
}
</script>
