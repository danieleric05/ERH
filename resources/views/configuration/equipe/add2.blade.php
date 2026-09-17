@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-text-primary mb-4">Équipes</h1>
            <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                    <i class="fa fa-home"></i> Accueil
                </a>
                <span class="text-text-secondary">/</span>
                <span>Configuration</span>
                <span class="text-text-secondary">/</span>
                <span class="text-text-primary font-semibold">Équipes</span>
            </nav>
        </div>
    </div>

    @include('success')
    @include('errors')

    <!-- Formulaire d'ajout -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8">
        <h2 class="text-lg font-bold text-text-primary mb-6">Ajouter une équipe</h2>
        <form action="{{ url('add/equipes/addequipes') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Identifiant</label>
                    <input type="text" name="id" required placeholder="Identifiant"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Équipe</label>
                    <input type="text" name="label" required placeholder="Nom de l'équipe"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                    <select name="uniteid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($unites as $unite)
                            <option value="{{ $unite->id }}">{{ $unite->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Chef d'équipe</label>
                    <select name="chefEquipeid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($Travailleur as $liste)
                            <option value="{{ $liste->id }}">{{ $liste->nom }} - {{ $liste->prenom }} - {{ $liste->matricule }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text-primary mb-2">Un mot sur l'équipe</label>
                    <textarea name="description" rows="4" placeholder="Un mot sur l'équipe"
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"></textarea>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-lg hover:bg-plastica-blue transition">
                Enregistrer
            </button>
        </form>
    </div>

    <!-- Liste -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Identifiant</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Équipe</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Unité</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data_equipe as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary">{{ $listedata->id }}</td>
                        <td class="px-6 py-4 text-text-primary">{{ $listedata->label }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ optional(\App\Unites::where('id', $listedata->departementid)->first())->label }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="openEquipeEdit({{ $listedata->id }}, @js($listedata->label), @js((string) $listedata->departementid), @js((string) $listedata->chefEquipe), @js($listedata->description))"
                                        title="MODIFIER"
                                        class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" onclick="deleteEquipe({{ $listedata->id }})"
                                        title="SUPPRIMER"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-text-secondary">Aucune équipe</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('configuration.equipe.edit')

    <script>
        function deleteEquipe(id) {
            if (!confirm("Êtes-vous sûr de vouloir supprimer cette équipe ?")) return;
            fetch('{{ url('delete/equipes/data') }}?id=' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(r => r.json())
            .then(data => { if (data === 'success') { window.location.reload(); } else { alert("Erreur lors de la suppression."); } })
            .catch(() => alert("Erreur lors de la suppression."));
        }
    </script>

@endsection
