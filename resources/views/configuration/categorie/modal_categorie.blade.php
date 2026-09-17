<div x-data="{ isOpen: false }" @open-modal-categorie-add.window="isOpen = true" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="isOpen = false"></div>
    <div class="relative bg-white rounded-lg shadow-2xl max-w-lg w-full mx-4 overflow-hidden" @click.stop>
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Ajouter une catégorie</h3>
            <button @click="isOpen = false" class="text-text-secondary hover:text-text-primary text-2xl leading-none">&times;</button>
        </div>
        <form onsubmit="return submitCategorieAdd(event)" class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-text-primary mb-2">Catégorie</label>
                <input type="text" name="label" required placeholder="Catégorie"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="isOpen = false" class="px-4 py-2 border border-slate-300 rounded-lg text-text-secondary hover:bg-slate-50 transition">Fermer</button>
                <button type="submit" class="px-4 py-2 bg-primary-accent text-white rounded-lg hover:bg-plastica-blue transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function submitCategorieAdd(e) {
    e.preventDefault();
    fetch('{{ url('add/categories/addcategories') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: new FormData(e.target),
    })
    .then(r => r.json())
    .then(data => { if (data === 'success') { window.location.reload(); } else { alert("Erreur lors de l'enregistrement."); } })
    .catch(() => alert("Erreur lors de l'enregistrement."));
    return false;
}
</script>
