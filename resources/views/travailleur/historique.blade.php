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
                    <li class="breadcrumb-item">Recherches</li>
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
                                <label for="type_id" class="control-label">Type</label>
                                <select required name="type_id" id="type_id" class="form-control select2-active">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">Embauché</option>
                                    <option value="2">Journalier</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" id="recherchechoix">
                            <div class="form-group">
                                <label for="recherche" class="control-label">Recherche par</label>
                                <select required name="recherche" id="recherche" class="form-control select2-active">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">Unité</option>
                                    <option value="2">Toutes les Unités</option>
                                    <option value="3">Période(date debut de contrat)</option>
                                    <option value="4">Journalier en fin de contrat</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="display: none" id="idunitechoix">
                            <div class="form-group">
                                <label for="uniteid" class="control-label">Unité</label>
                                <select name="uniteid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_unites as $date)
                                        <option value="{{ $date->id }}">{{ $date->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="display: none" id="date_debut">
                            <div class="form-group">
                                <label for="phone" class="control-label">Début</label>
                                <input type="date"  value="<?= date('Y-m-d')?>" name="beginn" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="display: none" id="date_fin">
                            <div class="form-group">
                                <label for="phone" class="control-label">Fin</label>
                                <input type="date" value="<?= date('Y-m-d')?>" max="<?= date('Y-m-d') ?>" name="endd" class="form-control text-uppercase" maxlength="50">
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
                                        <td><?= $rech->matricule ?></td>
                                        <td><?= $rech->nom ?> <?= $rech->prenom ?></td>
                                        <td>
										@foreach($data_unites as $unite)
											@if($unite->id == $rech->uniteid)
												{{ $unite->label }}
											@endif
										@endforeach
										</td>
                                        <td><?= $rech->date_debut_contrat ?></td>
                                        <td><?= $rech->date_fin_contrat ?></td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>

                        <div class="text-right">

                            <a href="{{ route('excel_download', $code) }}">
                                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-primary">
                                    SAGE <i class="icon-folder" aria-hidden="true"></i>
                                </button>
                            </a>
							
							<a href="{{ route('excel_download_quinzaine', $code) }}">
                                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-danger">
                                    Download Paie <i class="icon-refresh" aria-hidden="true"></i>
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