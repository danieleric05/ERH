@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des travailleurs
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des travailleurs</li>
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

                    <form action="{{ url('post_search') }}" method="POST" role="form">
    @csrf

                        <div class="row clearfix">

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Début</label>
                                <input type="date"  value="<?= date('Y-m-d')?>" name="beginn" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Fin</label>
                                <input type="date" value="<?= date('Y-m-d')?>" max="<?= date('Y-m-d') ?>" name="endd" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="contratid" class="control-label">Contrat</label>
                                <select required name="contratid" class="form-control select2-active">
                                    <option value="">-DEOULER-</option>
                                    <option value="2">Embauché</option>
                                    <option value="5">Declarations</option>
                                    <option value="6">Reconduire</option>
                                    <option value="3">Cessations</option>
                                    <option value="4">Certificat de travail</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="margin-top: 28px">
                            <button type="submit" class="btn btn-danger" style="padding-left: 70px; font-weight: bold; font-size: 14px; padding-right: 70px; padding-top: 7px; padding-bottom: 6px">
                                Rechercher
                            </button>
                        </div>

                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th>Unité</th>
                                <th>Equipe</th>
                                <th>Date d'embauche</th>
                                <th>Date fin de contrat</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!($recherches))
                                    AUCUNE DONNÉE DISPONIBLE
                            @endif
                            @if($recherches)
                                @foreach($recherches as $rech)
                                    <tr>
                                        <td><?= $rech->id ?></td>
                                        <td><?= $rech->id ?></td>
                                        <td><?= $rech->id ?></td>
                                        <td><?= $rech->id ?></td>
                                        <td><?= $rech->id ?></td>
                                        <td><?= $rech->id ?></td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>

                        <div class="text-right">

                            <a href="{{ route('exelstravailleurs', $code) }}">
                                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-primary">
                                    <i class="icon-folder" aria-hidden="true"></i>
                                </button>
                            </a>

                            <a href="{{ route('listetravailleurs') }}">
                                <button title="PDF" style="padding-left: 20px; padding-right: 20px" href="#" type="button" class="btn btn-dark">
                                    <i class="icon-docs" aria-hidden="true"></i>
                                </button>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('configuration.niveauEtude.modal_niveauEtude')


@endsection