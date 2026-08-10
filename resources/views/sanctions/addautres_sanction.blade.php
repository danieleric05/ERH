@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="{{ route('listesanctions') }}" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des sanctions </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter une sanction(non inscrit dans la base)</li>
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

                <form action="{{ url('post_sanction') }}" method="POST" role="form">
    @csrf

                <div class="body">
                    <div class="row clearfix">

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="travailleurid" class="control-label">Demandeur</label>
                                <select name="demandeurid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="concerneid" class="control-label">Employé(s)</label>
                                <select name="concerneid[]" multiple class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleurJour as $trav)
                                        <option value="{{ $trav->matricule }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tenuerecu" class="control-label">Motif</label>
                                <select name="motif" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">ABSENCE INJUSTIFIEE</option>
                                    <option value="2">INSUBORDINATION</option>
                                    <option value="3">RETARD REPETITIF</option>
                                    <option value="4">FAUTE LOURDE</option>
                                    <option value="5">INSUFFISANCE DE RENDEMENT</option>
                                    <option value="6">NEGLIGENCE PROFESSIONNELLE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="expose_motif" class="control-label">Exposé des motifs</label>
                                <textarea rows="3" class="form-control" name="expose_motif"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Date</label>
                            <div class="input-group mb-3">
                                <input name="datesanction" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tenuerecu" class="control-label">Sanction appliquée</label>
                                <select name="sanction_applique" id="sanction_applique" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">AVERTISSEMENT</option>
                                    <option value="2">MISE A PIED</option>
                                    <option value="3">LICENCIEMENT</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12" style="display: none" id="nombre_jour">
                            <label>Nombre de jour de la mise a pied</label>
                            <div class="input-group mb-3">
                                <input name="nombre_jour" style="height: 40px; color: black; font-weight: bold" type="text" class="form-control">
                            </div>
                        </div>

                        <!--<div class="col-lg-4 col-md-6 col-sm-12" style="display: none" id="nombre_jour">
                            <label>Date debut</label>
                            <div class="input-group mb-3">
                                <input name="debut" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12" style="display: none" id="nombre_jour">
                            <label>Date fin</label>
                            <div class="input-group mb-3">
                                <input name="fin" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>
                        -->


                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listesanctions') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-danger">
                                    Liste
                                </button>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="body" style="display: none">
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-12">
                            <p><b>Basic Example</b></p>
                            <div id="nouislider_basic_example"></div>
                            <div class="m-t-20 font-12"><b>Value: </b><span class="js-nouislider-value"></span></div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <p><b>Range Example</b></p>
                            <div id="nouislider_range_example"></div>
                            <div class="m-t-20 font-12"><b>Value: </b><span class="js-nouislider-value"></span></div>
                        </div>
                    </div>
                </div>

                </form>

            </div>
        </div>

    </div>

@endsection