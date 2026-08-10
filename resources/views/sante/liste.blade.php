@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des consultations
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Consultations</li>
                    <li class="breadcrumb-item active">Liste des consultations</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">
            <div class="card">

                @include('success')
                @include('errors')

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Infirmier</th>
                                <th>Matricule</th>
                                <th>Travailleur</th>
                                @if( (Auth::user()->idrole == 4))
                                <th>Consultation</th>
                                <th>Prescription</th>
                                @endif
                                <th class="text-center">Arret travail</th>
                                <th class="text-center">Debut</th>
                                <th class="text-center">Fin</th>
                                <th class="text-center">Statut</th>
                                <th>Enregistré le</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($listeSante as $listedata)
                                @php($travailleur = $travailleursById->get($listedata->travailleurid))
                                <tr>

                                    <td>
                                        {{ optional($usersById->get($listedata->userid))->name }}
                                    </td>
                                    <td title="{{ optional($travailleur)->matricule }}">
                                        {{ optional($travailleur)->matricule }}
                                    </td>
                                    <td title="{{ optional($travailleur)->matricule }}">{{ optional($travailleur)->nom }} {{ optional($travailleur)->prenom }}</td>
                                    @if( (Auth::user()->idrole == 4))
                                    <td>{{ $listedata->consultation }}</td>
                                    <td>{{ $listedata->prescription }}</td>
                                    @endif
                                    <td>
                                        @if($listedata->arret_travail == 1)
                                            <span class="badge badge-danger">AVEC ARRET DE TRAVAIL</span>
                                        @endif
                                        @if($listedata->arret_travail == 0)
                                            <span class="badge badge-success">SANS ARRET DE TRAVAIL</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $listedata->debut_arret }}
                                    </td>
                                    <td>
                                        {{ $listedata->fin_arret }}
                                    </td>
                                    <td>
                                        @if( ($listedata->statutid == 1) && ($listedata->arret_travail == 1) )
                                            <span class="badge badge-info">NON RECU PAR DRH</span>
                                        @endif
                                        @if( ($listedata->statutid == 2) && ($listedata->arret_travail == 1) )
                                            <span class="badge badge-dark">RECU PAR {{ optional($usersById->get($listedata->recu_par))->name }}</span>
                                        @endif
                                        @if( ($listedata->statutid == 3) && ($listedata->arret_travail == 1) )
                                            <span class="badge badge-dark">RECU PAR DRH && <br/> Ajouté aux variables</span>
                                        @endif
                                    </td>

                                    <td>{{ $listedata->created_at }}</td>

                                    <td class="actions text-center">

                                        @if( (Auth::user()->idrole == 1) || (Auth::user()->idrole == 2))

                                            <a title="Arret travail reçu" href="{{ route('patientrexu', $listedata->id) }}" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" data-toggle="tooltip" aria-describedby="tooltip270584">
                                                <i class="icon-reload" aria-hidden="true"></i>
                                            </a>

                                            <a title="Ajouté aux variables"  style="color: #fff; font-weight: bold" @if($listedata->statutid != 3) href="{{ route('variables_sante', $listedata->id) }}" @endif  class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove" data-toggle="tooltip" aria-describedby="tooltip270584">
                                                @if($listedata->statutid != 3) <i class="icon-like" aria-hidden="true"></i> @endif
                                                @if($listedata->statutid == 3) <i class="icon-dislike" aria-hidden="true"></i> @endif
                                            </a>

                                        @endif

                                        @if( (Auth::user()->idrole == 4) )

                                            <a title="INFO" data-id="{{ $listedata->id }}" id="detail" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584">
                                                <i class="icon-pencil" aria-hidden="true"></i>
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

    @include('sante.modal_edit')

@endsection