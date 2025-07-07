@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestions des variables
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Variables</li>
                    <li class="breadcrumb-item active">Liste des variables(manuelle)</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">
            <div class="card">

                <a href="{{ url('ajouter-variable') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Type</th>
                                <th>Employés</th>
                                <th>Cas</th>
                                <th>Periode</th>
                                <th>Debut</th>
                                <th>Fin</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                        @foreach($variableM as $vari)
                            <tr>
                                <td title="{{ $vari->justification }}">
                                    @if($vari->type_variable == 1)
                                        DIMANCHE
                                    @endif
                                    @if($vari->type_variable == 2)
                                        FERIE
                                    @endif
                                    @if($vari->type_variable == 3)
                                        JOUR OUVRABLE
                                    @endif
                                </td>
                                <td>
                                    @foreach( unserialize($vari->travailleurid) as $servaiable )
                                        <span title="{{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->nom }} {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->matricule }}
                                        </span> <br/>
                                    @endforeach
                                </td>
                                <td>
                                    @if($vari->cas_variables == 1)
                                        RETARD D'ENROLEMENT
                                    @endif
                                    @if($vari->cas_variables == 2)
                                            DEFAUT DE POINTAGE
                                    @endif
                                    @if($vari->cas_variables == 3)
                                            OUBLI DE POINTAGE
                                    @endif
                                    @if($vari->cas_variables == 4)
                                            DEFAUT D'EMPREINTE
                                    @endif
                                </td>
                                <td>
                                    @if($vari->periode == 2)
                                        JOUR
                                    @endif
                                    @if($vari->periode == 1)
                                        NUIT
                                    @endif
                                </td>
                                <td>
                                    {{ $vari->debut }}
                                </td>
                                <td>
                                    {{ $vari->fin }}
                                </td>

                                <td>
                                    @if($vari->statutid == 1)
                                        <span class="badge badge-primary">Actif</span>
                                    @endif
                                    @if($vari->statutid == 2)
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>

                                <td>
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