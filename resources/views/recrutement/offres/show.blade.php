@extends('layouts.erh')

@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">{{ $offre->titre }}</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ route('dashboard') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <a href="{{ route('offres.index') }}" class="hover:text-text-primary">Offres d'emploi</a>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">{{ $offre->titre }}</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('offres.edit', $offre->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition">
                    <i class="fa fa-edit"></i> Modifier
                </a>
                @if($offre->statut !== 3)
                    <form action="{{ route('offres.close', $offre->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Clôturer cette offre ?')">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fa fa-lock"></i> Clôturer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Détails de l'offre -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg-soft p-8">
            <p class="text-sm text-text-secondary mb-6">Créée le {{ $offre->created_at->format('d/m/Y à H:i') }}</p>

            <!-- Statut -->
            <div class="mb-6">
                <h5 class="font-semibold text-text-primary mb-2">Statut</h5>
                @if($offre->statut === 1)
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Publiée</span>
                @elseif($offre->statut === 2)
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Clôturée</span>
                @else
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Pourvue</span>
                @endif
            </div>

            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h5 class="font-semibold text-sm text-text-primary mb-1">Type de contrat</h5>
                    <p class="text-text-secondary">{{ $offre->type_contrat }}</p>
                </div>
                <div>
                    <h5 class="font-semibold text-sm text-text-primary mb-1">Nombre de postes</h5>
                    <p class="text-text-secondary">{{ $offre->nombre_postes }}</p>
                </div>
                <div>
                    <h5 class="font-semibold text-sm text-text-primary mb-1">Département</h5>
                    <p class="text-text-secondary">{{ $offre->departement->libelle ?? '-' }}</p>
                </div>
                <div>
                    <h5 class="font-semibold text-sm text-text-primary mb-1">Fonction</h5>
                    <p class="text-text-secondary">{{ $offre->fonction->libelle ?? '-' }}</p>
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-t border-slate-200 pt-6">
                <div>
                    <h5 class="font-semibold text-sm text-text-primary mb-1">Date de publication</h5>
                    <p class="text-text-secondary">{{ $offre->date_publication->format('d/m/Y') }}</p>
                </div>
                @if($offre->date_cloture)
                    <div>
                        <h5 class="font-semibold text-sm text-text-primary mb-1">Date de clôture</h5>
                        <p class="text-text-secondary">{{ $offre->date_cloture->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="border-t border-slate-200 pt-6 mb-6">
                <h5 class="font-semibold text-text-primary mb-2">Description</h5>
                <div class="text-text-secondary whitespace-pre-wrap">{{ $offre->description }}</div>
            </div>

            <!-- Compétences -->
            @if($offre->competences_requises)
                <div class="border-t border-slate-200 pt-6 mb-6">
                    <h5 class="font-semibold text-text-primary mb-2">Compétences requises</h5>
                    <div class="text-text-secondary whitespace-pre-wrap">{{ $offre->competences_requises }}</div>
                </div>
            @endif

            <!-- Expérience -->
            @if($offre->experience_requise)
                <div class="border-t border-slate-200 pt-6 mb-6">
                    <h5 class="font-semibold text-text-primary mb-2">Expérience requise</h5>
                    <p class="text-text-secondary">{{ $offre->experience_requise }}</p>
                </div>
            @endif

            <!-- Salaires -->
            @if($offre->salaire_min || $offre->salaire_max)
                <div class="border-t border-slate-200 pt-6">
                    <h5 class="font-semibold text-text-primary mb-2">Fourchette salariale</h5>
                    <p class="text-text-secondary">
                        @if($offre->salaire_min && $offre->salaire_max)
                            {{ number_format($offre->salaire_min, 0, ',', ' ') }} - {{ number_format($offre->salaire_max, 0, ',', ' ') }} FCFA
                        @elseif($offre->salaire_min)
                            À partir de {{ number_format($offre->salaire_min, 0, ',', ' ') }} FCFA
                        @elseif($offre->salaire_max)
                            Jusqu'à {{ number_format($offre->salaire_max, 0, ',', ' ') }} FCFA
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Sidebar - Statistiques -->
        <div class="bg-white rounded-lg shadow-lg-soft p-8">
            <h2 class="text-lg font-bold text-text-primary mb-6">Statistiques</h2>

            <div class="mb-6 pb-6 border-b border-slate-200">
                <h5 class="text-text-secondary text-sm font-semibold mb-2">Total candidatures</h5>
                <p class="text-3xl font-bold text-primary-accent">{{ $stats['total_candidatures'] }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <h5 class="text-text-secondary text-sm mb-1">Présélectionnées</h5>
                    <div class="flex items-center gap-2">
                        <div class="flex-grow bg-blue-100 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['preselectionnees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-text-primary">{{ $stats['preselectionnees'] }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-text-secondary text-sm mb-1">Entretiens</h5>
                    <div class="flex items-center gap-2">
                        <div class="flex-grow bg-green-100 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['entretiens'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-text-primary">{{ $stats['entretiens'] }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-text-secondary text-sm mb-1">Offres</h5>
                    <div class="flex items-center gap-2">
                        <div class="flex-grow bg-yellow-100 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['offres'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-text-primary">{{ $stats['offres'] }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-text-secondary text-sm mb-1">Embauchées</h5>
                    <div class="flex items-center gap-2">
                        <div class="flex-grow bg-emerald-100 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['embauchees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-text-primary">{{ $stats['embauchees'] }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-text-secondary text-sm mb-1">Rejetées</h5>
                    <div class="flex items-center gap-2">
                        <div class="flex-grow bg-red-100 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['rejetees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-text-primary">{{ $stats['rejetees'] }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('candidatures.index') }}?offre={{ $offre->id }}"
               class="mt-6 flex items-center justify-center gap-2 w-full px-4 py-2 bg-primary-accent text-white font-semibold rounded-lg hover:bg-plastica-blue transition">
                <i class="fa fa-users"></i> Voir les candidatures
            </a>
        </div>
    </div>

@endsection
