@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Gestion des utilisateurs</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Administration</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Utilisateurs</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('ajouterutilisateur') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fa fa-plus"></i> Ajouter
                </a>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pseudo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Rôle</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Unité</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Statut</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data_utilisateurs ?? [] as $utilisateur)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-text-primary text-sm">{{ $utilisateur->name }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $utilisateur->pseudo }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $utilisateur->role->label ?? '-' }}</td>
                        <td class="px-6 py-4 text-text-secondary text-sm">{{ $utilisateur->unite->label ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($utilisateur->statut_id == 1)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Désactivé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('modifierutilisateur', $utilisateur->id) }}" title="Modifier"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                @if($utilisateur->id !== Auth::id())
                                    @if($utilisateur->statut_id == 1)
                                        <a href="{{ route('statututilisateur', $utilisateur->id) }}" title="Désactiver"
                                           onclick="return confirm('Désactiver cet utilisateur ? Il ne pourra plus se connecter.')"
                                           class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i class="fa fa-ban"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('statututilisateur', $utilisateur->id) }}" title="Réactiver"
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <i class="fa fa-check-circle"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-text-secondary">
                            Aucun utilisateur disponible
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
