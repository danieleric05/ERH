<div x-data="{ isOpen: false }" @open-modal-departement-edit.window="isOpen = true" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="isOpen = false"></div>
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" @click.stop>
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Modifier le département</h3>
            <button @click="isOpen = false" class="text-text-secondary hover:text-text-primary text-2xl leading-none">&times;</button>
        </div>
        <form onsubmit="return submitDepartementEdit(event)" class="p-6">
            <input type="hidden" name="id" id="departementEditId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Département</label>
                    <input type="text" name="label" id="departementEditLabel" required
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                    <select name="uniteid" id="departementEditUnite" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($unites as $liste)
                            <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text-primary mb-2">Description du département</label>
                    <textarea name="description" id="departementEditDescription" rows="4"
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="isOpen = false" class="px-4 py-2 border border-slate-300 rounded-lg text-text-secondary hover:bg-slate-50 transition">Fermer</button>
                <button type="submit" class="px-4 py-2 bg-primary-accent text-white rounded-lg hover:bg-plastica-blue transition">Modifier</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDepartementEdit(id, label, uniteid, description) {
    document.getElementById('departementEditId').value = id;
    document.getElementById('departementEditLabel').value = label;
    document.getElementById('departementEditUnite').value = uniteid;
    document.getElementById('departementEditDescription').value = description || '';
    window.dispatchEvent(new CustomEvent('open-modal-departement-edit'));
}
function submitDepartementEdit(e) {
    e.preventDefault();
    fetch('{{ url('update/departements/updatedepartements') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: new FormData(e.target),
    })
    .then(r => r.json())
    .then(data => { if (data === 'success') { window.location.reload(); } else { alert("Erreur lors de la modification."); } })
    .catch(() => alert("Erreur lors de la modification."));
    return false;
}
</script>
