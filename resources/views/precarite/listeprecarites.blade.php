@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestions des precarités
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Precarités</li>
                    <li class="breadcrumb-item active">Liste des precarités</li>
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
                                <th>N°</th>
                                <th class="text-center">Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th class="text-center">Nombre de jour</th>
                                <th class="text-center">Etat</th>
                            </tr>
                            </thead>
                            <?= $i=1 ?>
                            @foreach($Precarites as $rech)
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td class="text-center"><?= $rech->matricule ?></td>
                                    @if($rech->matricule)
                                    <td>{{ optional($travailleursByMatricule->get($rech->matricule))->nom }} {{ optional($travailleursByMatricule->get($rech->matricule))->prenom }}</td>
                                    @endif
                                    <td class="text-center"><?= $rech->valeur ?></td>
                                    <td class="text-center" >
                                        @if($rech->statutid == 2)
                                            <span class="badge badge-danger">Non Payé</span>
                                        @endif
                                        @if($rech->statutid == 3)
                                            <span class="badge badge-primary">Payé</span>
                                        @endif
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

@endsection