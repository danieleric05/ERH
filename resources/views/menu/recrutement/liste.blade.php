@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des travailleurs</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste des travailleurs</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Prénoms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Contacts</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date d'embauche</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fin de contrat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Département</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Équipe</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-900 font-semibold">Exemple</td>
                        <td class="px-6 py-4 text-text-primary">Nom</td>
                        <td class="px-6 py-4 text-text-primary">Prénom</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">+XXX XXX XXX</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">2024-01-15</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">2025-12-31</td>
                        <td class="px-6 py-4 text-text-primary">Département</td>
                        <td class="px-6 py-4 text-text-primary">Équipe</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button type="button"
                                        class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition"
                                        title="Modifier">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Supprimer">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection