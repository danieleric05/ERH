@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Historiques santé</h1>
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span class="text-text-secondary">/</span>
            <span class="text-text-primary font-semibold">Recherches</span>
        </nav>
    </div>

    <!-- Formulaire de recherche -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8">
        <form action="{{ url('post_historiques_sante') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Type</label>
                    <select required name="type_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">Embauché</option>
                        <option value="2">Journalier</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Unités</label>
                    <select required name="type_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">Embauché</option>
                        <option value="2">Journalier</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Début</label>
                    <input type="date" value="{{ date('Y-m-d') }}" name="beginn"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Fin</label>
                    <input type="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" name="endd"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Résultats -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom & Prénoms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Unité</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date d'embauche</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date fin de contrat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-text-secondary">Aucune donnée disponible</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 flex justify-end">
            <button title="EXCEL" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-accent text-white font-semibold rounded-lg hover:bg-plastica-blue transition">
                <i class="icon-folder"></i>
            </button>
        </div>
    </div>

@endsection
