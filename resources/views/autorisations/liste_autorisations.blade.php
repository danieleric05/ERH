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
                    <li class="breadcrumb-item">Autorisations</li>
                    <li class="breadcrumb-item active">Liste des autorisations</li>
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
                                <th>Motif</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>
                        @foreach($liste_auto as $auto)
                            <tr>
                                <td title="{{ $auto->commentaire }}">
                                     <span title="{{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->nom }} {{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ $trava = \App\Travailleur::where('id', $auto->demandeurid )->first()->matricule }}
                                      </span>
                                </td>
                                <td>{{ $auto->debut }}</td>
                                <td>{{ $auto->fin }}</td>
                                <td title="ville1 ville2">
                                    @if($auto->motif_absence == 1)
                                        MALADIE
                                    @endif
                                    @if($auto->motif_absence == 2)
                                        CONVENANCE PERSONNELLE
                                    @endif
                                    @if($auto->motif_absence == 3)
                                        PERMISSIONS EXCEPTIONNELLES
                                    @endif
                                    @if($auto->motif_absence == 4)
                                        CONGES PAYES
                                    @endif
                                    @if($auto->motif_absence == 5)
                                        CONGES SANS SOLDE
                                    @endif
                                    @if($auto->motif_absence == 7)
                                        AUTRES CAS
                                    @endif
                                </td>
                                <td>
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

                                    <a @if($auto->statutid == 1) title="AJOUTER AUX VARIABLES" @endif @if($auto->statutid == 3) title="AUTORISATION DEJA AJOUTEE AUX VARIABLES" @endif class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove" @if($auto->statutid == 1) href="{{ route('autorisationvariable', $auto->id) }}" @endif >
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