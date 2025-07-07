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
            <div class="card">

                <a href="{{ url('ajouter-heure-supplementaire') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Employes</th>
                                <th class="text-center">Nombre d'heure</th>
                                <th>Date</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>
                            @foreach($variableHS as $vari)
                             <tr>
                                 <td>
                                     @foreach( unserialize($vari->employer_hs) as $servaiable )
                                         <span title="{{ $trava = \App\Travailleur::where('id', $servaiable )->first()->nom }} {{ $trava = \App\Travailleur::where('id', $servaiable )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ $trava = \App\Travailleur::where('id', $servaiable )->first()->matricule }}
                                        </span> <br/>
                                     @endforeach
                                 </td>
                                <td class="text-center">
                                    {{ $vari->nbre_heure_hs }}
                                </td>
                                <td>{{ $vari->date_hs }}</td>

                                <td class="text-center">
                                    @if($vari->statutid == 1)
                                        <span class="badge badge-primary">Actif</span>
                                    @endif
                                    @if($vari->statutid == 2)
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