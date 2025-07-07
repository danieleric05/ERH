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
                                <th>Mode de transport</th>
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tr>
                                <td>KKKK</td>
                                <td>KKKKKKKKK</td>
                                <td>KKKK</td>
                                <td title="ville1 ville2">KKKKKKKKK</td>
                                <td>LLLLLLLLL</td>

                                <td>KKKK</td>
                                <td>KKKKKKKKK</td>
                                <td>LLLLLLLLL</td>

                                <td>
                                    <a title="MODIFIER" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" href="#" data-toggle="tooltip" data-original-title="Remove">
                                        <i class="icon-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a title="ANUULER" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" href="#" data-toggle="tooltip" data-original-title="Remove">
                                        <i class="icon-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>

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