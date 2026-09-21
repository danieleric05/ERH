@extends('erhform')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Recrutement</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Offres d'Emploi</a></li>
                    <li class="breadcrumb-item">{{ $offre->titre }}</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">
                <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Modifier
                </a>
                @if($offre->statut !== 3)
                <form action="{{ route('offres.close', $offre->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Clôturer cette offre ?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-lock"></i> Clôturer
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <!-- Offre Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="header">
                    <h2>{{ $offre->titre }}</h2>
                    <small>Créée le {{ $offre->created_at->format('d/m/Y à H:i') }}</small>
                </div>

                <div class="body">
                    <!-- Statut -->
                    <div class="mb-6">
                        <h5 class="font-semibold mb-2">Statut</h5>
                        @if($offre->statut === 1)
                            <span class="badge bg-success">Publiée</span>
                        @elseif($offre->statut === 2)
                            <span class="badge bg-warning">Clôturée</span>
                        @else
                            <span class="badge bg-secondary">Pourvue</span>
                        @endif
                    </div>

                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Type de Contrat</h5>
                            <p class="text-slate-600">{{ $offre->type_contrat }}</p>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Nombre de Postes</h5>
                            <p class="text-slate-600">{{ $offre->nombre_postes }}</p>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Département</h5>
                            <p class="text-slate-600">{{ $offre->departement->libelle ?? '-' }}</p>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Fonction</h5>
                            <p class="text-slate-600">{{ $offre->fonction->libelle ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-t pt-6">
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Date de Publication</h5>
                            <p class="text-slate-600">{{ $offre->date_publication->format('d/m/Y') }}</p>
                        </div>
                        @if($offre->date_cloture)
                        <div>
                            <h5 class="font-semibold text-sm mb-1">Date de Clôture</h5>
                            <p class="text-slate-600">{{ $offre->date_cloture->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="border-t pt-6 mb-6">
                        <h5 class="font-semibold mb-2">Description</h5>
                        <div class="text-slate-700 whitespace-pre-wrap">{{ $offre->description }}</div>
                    </div>

                    <!-- Compétences -->
                    @if($offre->competences_requises)
                    <div class="border-t pt-6 mb-6">
                        <h5 class="font-semibold mb-2">Compétences Requises</h5>
                        <div class="text-slate-700 whitespace-pre-wrap">{{ $offre->competences_requises }}</div>
                    </div>
                    @endif

                    <!-- Expérience -->
                    @if($offre->experience_requise)
                    <div class="border-t pt-6 mb-6">
                        <h5 class="font-semibold mb-2">Expérience Requise</h5>
                        <p class="text-slate-700">{{ $offre->experience_requise }}</p>
                    </div>
                    @endif

                    <!-- Salaires -->
                    @if($offre->salaire_min || $offre->salaire_max)
                    <div class="border-t pt-6">
                        <h5 class="font-semibold mb-2">Fourchette Salariale</h5>
                        <p class="text-slate-700">
                            @if($offre->salaire_min && $offre->salaire_max)
                                {{ number_format($offre->salaire_min, 0, ',', ' ') }} - {{ number_format($offre->salaire_max, 0, ',', ' ') }} €
                            @elseif($offre->salaire_min)
                                À partir de {{ number_format($offre->salaire_min, 0, ',', ' ') }} €
                            @elseif($offre->salaire_max)
                                Jusqu'à {{ number_format($offre->salaire_max, 0, ',', ' ') }} €
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Statistiques -->
        <div class="col-lg-4">
            <div class="card">
                <div class="header">
                    <h2>Statistiques</h2>
                </div>
                <div class="body">
                    <!-- Total Candidatures -->
                    <div class="mb-6 pb-6 border-b">
                        <h5 class="text-slate-600 text-sm font-semibold mb-2">Total Candidatures</h5>
                        <p class="text-3xl font-bold text-primary-accent">{{ $stats['total_candidatures'] }}</p>
                    </div>

                    <!-- Statuts -->
                    <div class="space-y-3">
                        <div>
                            <h5 class="text-slate-600 text-sm mb-1">Présélectionnées</h5>
                            <div class="flex items-center gap-2">
                                <div class="flex-grow bg-blue-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['preselectionnees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $stats['preselectionnees'] }}</span>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-slate-600 text-sm mb-1">Entretiens</h5>
                            <div class="flex items-center gap-2">
                                <div class="flex-grow bg-green-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['entretiens'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $stats['entretiens'] }}</span>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-slate-600 text-sm mb-1">Offres</h5>
                            <div class="flex items-center gap-2">
                                <div class="flex-grow bg-yellow-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['offres'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $stats['offres'] }}</span>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-slate-600 text-sm mb-1">Embauchées</h5>
                            <div class="flex items-center gap-2">
                                <div class="flex-grow bg-emerald-200 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['embauchees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $stats['embauchees'] }}</span>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-slate-600 text-sm mb-1">Rejetées</h5>
                            <div class="flex items-center gap-2">
                                <div class="flex-grow bg-red-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $stats['total_candidatures'] > 0 ? ($stats['rejetees'] / $stats['total_candidatures'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $stats['rejetees'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Button to Candidatures -->
                    <a href="{{ route('candidatures.index') }}?offre={{ $offre->id }}" class="btn btn-primary btn-block mt-6">
                        <i class="fa fa-users mr-2"></i>Voir les Candidatures
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
