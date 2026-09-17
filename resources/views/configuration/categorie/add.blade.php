@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Catégories</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Configuration</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Catégories</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal-categorie-add'))"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fa fa-plus"></i> Ajouter une catégorie
                </button>
            </div>
        </div>
    </div>

    @include('success')
    @include('errors')

    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Catégorie</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data_categories as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary">{{ $listedata->label }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="openCategorieEdit({{ $listedata->id }}, @js($listedata->label))"
                                        title="MODIFIER"
                                        class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" onclick="deleteCategorie({{ $listedata->id }})"
                                        title="SUPPRIMER"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-text-secondary">Aucune catégorie</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('configuration.categorie.modal_categorie')
    @include('configuration.categorie.edit')

    <script>
        function deleteCategorie(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) return;
            fetch('{{ url('delete/categories/data') }}?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(r => r.json())
            .then(data => { if (data === 'success') { window.location.reload(); } else { alert("Erreur lors de la suppression."); } })
            .catch(() => alert("Erreur lors de la suppression."));
        }
    </script>

@endsection
