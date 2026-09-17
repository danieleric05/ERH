<div x-data="{ isOpen: false }" @open-modal-departement-add.window="isOpen = true" class="fixed inset-0 z-50 flex items-center justify-center" x-show="isOpen" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="isOpen = false"></div>
    <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 overflow-hidden" @click.stop>
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-text-primary">Ajouter un département</h3>
            <button @click="isOpen = false" class="text-text-secondary hover:text-text-primary text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ url('add/departements/adddepartements') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Identifiant</label>
                    <input type="number" name="id" required placeholder="Identifiant"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Département</label>
                    <input type="text" name="label" required placeholder="Département"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                    <select name="uniteid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($unites as $liste)
                            <option value="{{ $liste->id }}">{{ $liste->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text-primary mb-2">Description du département</label>
                    <textarea name="description" rows="4" placeholder="Description du département"
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="isOpen = false" class="px-4 py-2 border border-slate-300 rounded-lg text-text-secondary hover:bg-slate-50 transition">Fermer</button>
                <button type="submit" class="px-4 py-2 bg-primary-accent text-white rounded-lg hover:bg-plastica-blue transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
