@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestion des variables</h1>
            <a href="{{ url('bienvenue') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span class="text-text-primary font-medium">Variables</span>
        </nav>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Variables manuelles Card -->
        <a href="{{ route('listevariables_manuelle') }}" class="group">
            <div class="bg-blue-600 rounded-lg shadow-lg p-6 text-center text-white hover:bg-blue-700 transition cursor-pointer h-full flex flex-col justify-center">
                <h3 class="text-4xl font-bold mb-2">0</h3>
                <span class="text-sm font-medium">Variables manuelles</span>
            </div>
        </a>

        <!-- Variables automatique Card -->
        <a href="{{ route('listevariables_automatique') }}" class="group">
            <div class="bg-gray-600 rounded-lg shadow-lg p-6 text-center text-white hover:bg-gray-700 transition cursor-pointer h-full flex flex-col justify-center">
                <h3 class="text-4xl font-bold mb-2">0</h3>
                <span class="text-sm font-medium">Variables automatique</span>
            </div>
        </a>

        <!-- Heures supplementaires Card -->
        <a href="{{ route('listevariables_heure_supp') }}" class="group">
            <div class="bg-amber-500 rounded-lg shadow-lg p-6 text-center text-white hover:bg-amber-600 transition cursor-pointer h-full flex flex-col justify-center">
                <h3 class="text-4xl font-bold mb-2">0</h3>
                <span class="text-sm font-medium">Heures supplementaires</span>
            </div>
        </a>

        <!-- Autres variables Card -->
        <a href="{{ route('listevariables_autres_variables') }}" class="group">
            <div class="bg-red-600 rounded-lg shadow-lg p-6 text-center text-white hover:bg-red-700 transition cursor-pointer h-full flex flex-col justify-center">
                <h3 class="text-4xl font-bold mb-2">0</h3>
                <span class="text-sm font-medium">Autres variables</span>
            </div>
        </a>
    </div>
</div>

@endsection
