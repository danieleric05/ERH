@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des congés
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Congés</li>
                    <li class="breadcrumb-item active">Liste des congés</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">

            @include('success')
            @include('errors')

            <div class="card">

                <a href="{{ route('ajouterConges') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <a href="{{ route('calendrier_conges') }}" style="float: right" class="btn btn-primary m-b-15 m-t-10 m-r-20">
                    <i class="icon-calendar" aria-hidden="true"></i> Calendrier
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Travailleur</th>
                                <th>Type</th>
                                <th>Debut</th>
                                <th>Fin</th>
                                <th class="text-center">Nombre de jours</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($liste_conges as $conge)
                                @php($travailleur = $travailleursById->get($conge->travailleurid))
                                <tr>
                                    <td title="{{ $conge->justification }}">
                                        <span title="{{ optional($travailleur)->nom }} {{ optional($travailleur)->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ optional($travailleur)->matricule }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($conge->type_conge == 1) CONGE ANNUEL @endif
                                        @if($conge->type_conge == 2) CONGE MALADIE @endif
                                        @if($conge->type_conge == 3) CONGE MATERNITE/PATERNITE @endif
                                        @if($conge->type_conge == 4) CONGE SANS SOLDE @endif
                                        @if($conge->type_conge == 5) CONGE EXCEPTIONNEL @endif
                                    </td>
                                    <td>{{ $conge->debut }}</td>
                                    <td>{{ $conge->fin }}</td>
                                    <td class="text-center">{{ $conge->nombre_jours }}</td>
                                    <td class="text-center">
                                        @if($conge->statutid == 1)
                                            <span class="badge badge-warning">EN ATTENTE</span>
                                        @endif
                                        @if($conge->statutid == 2)
                                            <span class="badge badge-success">VALIDE</span>
                                        @endif
                                        @if($conge->statutid == 3)
                                            <span class="badge badge-danger">REFUSE</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($conge->statutid == 1)
                                            <a href="{{ route('validerConge', $conge->id) }}" title="Valider" class="btn btn-sm btn-icon btn-pure btn-success on-default">
                                                <i class="icon-check" aria-hidden="true"></i>
                                            </a>
                                            <a onclick="return confirm('Êtes-vous sûr de vouloir refuser cette demande ?')" href="{{ route('refuserConge', $conge->id) }}" title="Refuser" class="btn btn-sm btn-icon btn-pure btn-danger on-default">
                                                <i class="icon-close" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
