@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Gestions des variables (manuelle)</h1>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-primary-accent">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Configuration</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Liste des variables (manuelle)</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Employés</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Cas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Début</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Fin</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nombre de jour</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Justification</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_variables_manuelle ?? [] as $item)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->type ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->employe ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->cas ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->debut ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->fin ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->nombre_jour ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->justification ?? '-' }}</td>
                            <td class="px-6 py-4 text-center text-sm">
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">Actif</span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a title="Modifier" href="#" class="inline-flex items-center gap-2 px-3 py-1 text-slate-700 hover:text-slate-900 transition">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a title="Supprimer" href="#" class="inline-flex items-center gap-2 px-3 py-1 text-red-600 hover:text-red-800 transition">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-text-secondary">Aucune donnée disponible</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('tenues.modal_edit')

@endsection
