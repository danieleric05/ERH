<!-- Alpine.js Modal for Adding Consultation Category -->
<div x-data="modalSante()" @open-modal-sante.window="openModal()" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 overflow-hidden" @click.stop>
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Ajouter une Catégorie de Consultation</h3>
            <button @click="closeModal()" class="text-text-secondary hover:text-text-primary text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form action="{{ url('add/santes/addsantes') }}" method="POST" class="p-6">
            @csrf

            <div class="mb-6">
                <label for="label" class="block text-sm font-medium text-text-primary mb-2">Catégorie</label>
                <input required type="text" name="label" id="label"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    placeholder="Entrez la catégorie de consultation"
                    x-model="categoryLabel">
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
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
function modalSante() {
    return {
        isOpen: false,
        categoryLabel: '',

        openModal() {
            this.isOpen = true;
            this.categoryLabel = '';
        },

        closeModal() {
            this.isOpen = false;
            this.categoryLabel = '';
        }
    }
}

// Global function to trigger modal
window.openSanteModal = function() {
    window.dispatchEvent(new CustomEvent('open-modal-sante', { detail: {} }));
}
</script>
