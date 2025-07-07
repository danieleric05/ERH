@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestions des autorisations
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Missions</li>
                    <li class="breadcrumb-item active">Liste des missions</li>
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

                <a href="{{ url('ajouter-autorisation') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Demandeur</th>
                                <th>Debut</th>
                                <th>Fin</th>
                                <th>Pays</th>
                                <th>Nuitée</th>
                                <th>Journée</th>
                                <th class="text-center">Mode de transport</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            @foreach($liste_mission as $auto)
                                <tr>

                                    <td title="{{ $auto->commentaire }}">
                                     <span title="{{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->nom }} {{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->matricule }}
                                      </span>
                                    </td>

                                    <td class="text-center">{{ $auto->debut }}</td>
                                    <td class="text-center">{{ $auto->fin }}</td>

                                    <td class="text-center">{{ $auto->pays }}</td>
                                    <td>{{ $auto->nuitee }}</td>

                                    <td class="text-center">
                                        {{ $auto->journee }}
                                    </td>

                                    <td class="text-center">
                                        @if($auto->mode_transport == 1)
                                            <span class="badge badge-primary">AVION</span>
                                        @endif
                                        @if($auto->mode_transport == 2)
                                            <span style="font-weight: bold" class="badge badge-danger">BATEAU</span>
                                        @endif
                                        @if($auto->mode_transport == 3)
                                            <span style="font-weight: bold" class="badge badge-danger">TRAIN</span>
                                        @endif
                                        @if($auto->mode_transport == 4)
                                            <span style="font-weight: bold" class="badge badge-danger">VEHICULE</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($auto->statutid == 1)
                                            <span class="badge badge-primary">Actif</span>
                                        @endif
                                        @if($auto->statutid == 2)
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <a title="MODIFIER" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" href="#" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a title="ANUULER" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" href="#" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="icon-trash" aria-hidden="true"></i>
                                        </a>

                                        <a @if($auto->statutid == 1) title="AJOUTER AUX VARIABLES" @endif @if($auto->statutid == 3) title="AUTORISATION DEJA AJOUTEE AUX VARIABLES" @endif class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove" @if($auto->statutid == 1) href="{{ route('missionvariable', $auto->id) }}" @endif >
                                            <i class="icon-bag" aria-hidden="true"></i>
                                        </a>

                                    </td>

                                </tr>
                            @endforeach

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('tenues.modal_edit')

@endsection