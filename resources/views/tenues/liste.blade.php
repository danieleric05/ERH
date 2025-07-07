@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestions des tenues
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Tenues</li>
                    <li class="breadcrumb-item active">Gestions des tenues</li>
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

                <a href="{{ route('stock_tenues') }}" title="VOIR LE STOCK" style="float: right" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon-eye" aria-hidden="true"></i> Stock
                </a>

                <a href="{{ url('ajouter-tenue') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th>Services</th>
                                <th class="text-center">Date de reception</th>
                                <th class="text-center">Reçu</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($tenues as $listedata)
                                <tr>

                                    <td>
                                        {{ $prenom = \App\Travailleur::where('id', $listedata->travailleurid)->first()->matricule }}
                                    </td>
                                    <td title="MATRICULE : {{ $matr = \App\Travailleur::where('id', $listedata->travailleurid)->first()->matricule }}">{{ $travail = \App\Travailleur::where('id', $listedata->travailleurid)->first()->nom }} {{ $prenom = \App\Travailleur::where('id', $listedata->travailleurid)->first()->prenom }}</td>
                                    <td>{{ $travail = \App\Services_tenue::where('id', $listedata->services)->first()->label }}</td>

                                    <td title=" Date et heure de reception : {{ $listedata->created_at }}">{{ $listedata->datereception }}</td>

                                    <td class="text-center"  title="Détail tenue : {{ $listedata->detail_tenue }} ; Détail chaussure : {{ $listedata->detail_chaussure }}">
                                        <span class="badge badge-primary" style="font-weight: bold;">{{ $matr = \App\ArticleRecu::where('id', $listedata->tenuerecu)->first()->label }}</span>
                                    </td>

                                    <td class="text-center">
                                        @if($listedata->etat == 1)
                                            <span style="font-weight: bold;" class="badge badge-success">BON ETAT</span>
                                        @endif
                                        @if($listedata->etat == 2)
                                            <span style="font-weight: bold;" class="badge badge-danger">MAUVAIS ETAT</span>
                                        @endif
                                    </td>

                                    <td class="actions text-center">
                                        @if($listedata->etat == 1)
                                            <a title="CHANGER L'ETAT DE LA TENUE" href="{{ url('chager_etat', $listedata->id  ) }}" class="btn btn-sm btn-icon btn-pure btn-info on-default button-remove" data-toggle="tooltip" aria-describedby="tooltip270584">
                                                <i class="icon-map" aria-hidden="true"></i>
                                            </a>
                                        @endif

                                        <a title="Modifier" href="{{ url('edit/tenues/data') }}" data-id="{{ $listedata->id }}" id="edit" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip" aria-describedby="tooltip270584">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>

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

    @include('tenues.modal_edit')

@endsection