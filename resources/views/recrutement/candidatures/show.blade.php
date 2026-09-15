@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">
                    {{ $candidature->candidat->nom ?? '' }} {{ $candidature->candidat->prenom ?? '' }}
                </h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ route('dashboard') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <a href="{{ route('candidatures.index') }}" class="hover:text-text-primary">Candidatures</a>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Détail</span>
                </nav>
            </div>
            <a href="{{ route('candidatures.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @include('success')
    @include('errors')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Infos candidat -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg-soft p-6">
            <h2 class="text-lg font-bold text-text-primary mb-4">Informations du candidat</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-text-secondary">Email</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->candidat->email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Téléphone</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->candidat->telephone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Offre visée</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->offre->titre ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Date de candidature</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->date_candidature }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Source</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->candidat->source ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-text-secondary">Expérience</dt>
                    <dd class="text-text-primary font-medium">{{ $candidature->candidat->annee_experience ?? 0 }} an(s)</dd>
                </div>
            </dl>

            @if($candidature->notes)
                <div class="mt-6">
                    <dt class="text-text-secondary text-sm">Notes</dt>
                    <dd class="text-text-primary text-sm mt-1">{{ $candidature->notes }}</dd>
                </div>
            @endif

            @if($candidature->rejetee_raison)
                <div class="mt-6 p-4 bg-red-50 rounded-lg">
                    <dt class="text-red-800 text-sm font-semibold">Motif de rejet</dt>
                    <dd class="text-red-700 text-sm mt-1">{{ $candidature->rejetee_raison }}</dd>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <h2 class="text-lg font-bold text-text-primary mb-4">Statut</h2>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full mb-4"
                      style="background-color: {{ $candidature->statut->couleur ?? '#94a3b8' }}22; color: {{ $candidature->statut->couleur ?? '#64748b' }};">
                    {{ $candidature->statut->libelle ?? 'Reçu' }}
                </span>
                <form method="POST" action="{{ route('candidatures.statut', $candidature->id) }}">
                    @csrf
                    <select name="statut_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg mb-3">
                        @foreach($statuts as $statut)
                            <option value="{{ $statut->id }}" @selected($candidature->statut_id == $statut->id)>{{ $statut->libelle }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Mettre à jour le statut
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <h2 class="text-lg font-bold text-text-primary mb-4">Recruteur assigné</h2>
                <form method="POST" action="{{ route('candidatures.assigner', $candidature->id) }}">
                    @csrf
                    <select name="assignee_a" class="w-full px-3 py-2 border border-slate-300 rounded-lg mb-3">
                        <option value="">-- Aucun --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($candidature->assignee_a == $user->id)>{{ $user->pseudo }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
                        Assigner
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-lg-soft p-6">
                <h2 class="text-lg font-bold text-text-primary mb-4">Rejeter la candidature</h2>
                <form method="POST" action="{{ route('candidatures.rejeter', $candidature->id) }}"
                      onsubmit="return confirm('Confirmer le rejet de cette candidature ?')">
                    @csrf
                    <textarea name="raison" rows="3" placeholder="Motif du rejet"
                              class="w-full px-3 py-2 border border-slate-300 rounded-lg mb-3" required></textarea>
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Rejeter
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
