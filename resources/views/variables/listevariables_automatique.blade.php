@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des variables automatiques</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Variables</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Automatiques</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Employés</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Variables</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nombre de jours</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($variableA ?? [] as $vari)
                    <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                    {{ $vari->employe ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $vari->variable ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    {{ $vari->nombre_jour ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($vari->statutid == 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Actif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Edit Button --}}
                                        <a title="Modifier"
                                           href="#"
                                           class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a title="Annuler"
                                           href="#"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"
                                           class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-lg">Aucune variable automatique trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('tenues.modal_edit')

@endsection