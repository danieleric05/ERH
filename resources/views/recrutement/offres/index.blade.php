@extends('erhform')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Recrutement</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Offres d'Emploi</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-12">
            @include('success')
            @include('errors')

            <div class="card">
                <div class="header d-flex justify-content-between align-items-center">
                    <h2>Offres d'Emploi</h2>
                    <a href="{{ route('offres.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>Nouvelle Offre
                    </a>
                </div>

                <div class="body">
                    <!-- Search and Filter -->
                    <div class="mb-6 flex justify-end">
                        <form id="searchForm" class="flex items-center max-w-lg gap-2">
                            <input type="text" placeholder="Rechercher..."
                                   class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                                   id="searchInput">
                            <select class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white"
                                    id="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="1">Publiée</option>
                                <option value="2">Clôturée</option>
                                <option value="3">Pourvue</option>
                            </select>
                        </form>
                    </div>

                    <!-- Offres Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th>Titre</th>
                                    <th>Type Contrat</th>
                                    <th>Département</th>
                                    <th>Fonction</th>
                                    <th>Postes</th>
                                    <th>Statut</th>
                                    <th>Date Cloture</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offres as $offre)
                                <tr>
                                    <td>
                                        <a href="{{ route('offres.show', $offre->id) }}" class="font-medium text-primary-accent hover:underline">
                                            {{ $offre->titre }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $offre->type_contrat === 'CDI' ? 'bg-green-500' : ($offre->type_contrat === 'CDD' ? 'bg-blue-500' : 'bg-orange-500') }} text-white px-2 py-1 rounded">
                                            {{ $offre->type_contrat }}
                                        </span>
                                    </td>
                                    <td>{{ $offre->departement->libelle ?? '-' }}</td>
                                    <td>{{ $offre->fonction->libelle ?? '-' }}</td>
                                    <td class="text-center">{{ $offre->nombre_postes }}</td>
                                    <td>
                                        @if($offre->statut === 1)
                                            <span class="badge bg-success">Publiée</span>
                                        @elseif($offre->statut === 2)
                                            <span class="badge bg-warning">Clôturée</span>
                                        @else
                                            <span class="badge bg-secondary">Pourvue</span>
                                        @endif
                                    </td>
                                    <td>{{ $offre->date_cloture ? $offre->date_cloture->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info" title="Voir détails">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning" title="Éditer">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($offre->statut !== 3)
                                            <form action="{{ route('offres.close', $offre->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Clôturer cette offre ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Clôturer">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-slate-500">
                                        Aucune offre d'emploi trouvée.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $offres->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    // Implémentation de la recherche côté client ou redirection vers GET avec paramètres
});
</script>
@endsection
