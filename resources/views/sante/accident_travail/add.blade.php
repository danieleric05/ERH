@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="{{ route('listesconsultation') }}" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des accidents de travail </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter un accident de travail</li>
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

                {!!Form::open (['url'=>['post_accident_travail'], 'method'=>'post', 'role'=>'form'])!!}

                <div class="body">
                    <div class="row clearfix">

                        <div class="col-lg-5 col-md-6 col-sm-12" style="font-size: 18px; font-weight: bold; color: #000">
                            <div class="form-group">
                                <label for="travailleurid" class="control-label">Travailleur</label>
                                <select name="travailleurid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="font-size: 18px; font-weight: bold; color: #000">
                            <label>Date</label>
                            <div class="input-group mb-3">
                                <input name="dateconsul" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12" style="font-size: 18px; font-weight: bold; color: #000">
                            <div class="form-group">
                                <label for="cause" class="control-label">Cause détaillée de l'accident de travail </label>
                                <textarea name="cause" class="form-control" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12" style="font-size: 18px; font-weight: bold; color: #000">
                            <div class="form-group">
                                <label for="prescription" class="control-label">Prescription</label>
                                <textarea name="prescription" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
						
						<div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group" style="font-size: 18px; font-weight: bold; color: #000">
                                <label for="arret_travail" class="control-label">Arret de travail</label>
                                <select style="font-size: 18px; font-weight: bold; color: #000" id="arret_travail" name="arret_travail" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">Oui</option>
                                    <option value="0">Non</option>
                                </select>
                            </div>
                        </div>
						
						<div class="col-lg-3 col-md-6 col-sm-12" style="display: none; font-size: 18px; font-weight: bold; color: #000" id="cause_arret">
                            <div class="form-group" style="font-size: 18px; font-weight: bold; color: #000">
                                <label for="cause_arret_travail" class="control-label">Cause arret de travail</label>
                                <select style="font-size: 18px; font-weight: bold; color: #000" name="cause_arret_travail" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="Maladie Professionnelle">Maladie Professionnelle</option>
                                    <option selected value="Accident de travail">Accident de travail</option>
                                    <option value="Maladie">Maladie</option>
                                    <option value="Autres">Autres</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12"  style="display: none; font-size: 18px; font-weight: bold; color: #000" id="debut_fin">
                            <label>Début et fin</label>
                            <div class="input-daterange input-group" style="font-size: 18px; font-weight: bold; color: #000">
                                <span class="input-group-addon text-center" style="width: 40px;">Du</span>
                                <input style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date"   class="input-sm form-control" name="debut_arret">
                                <span class="input-group-addon text-center" style="width: 40px;">Au</span>
                                <input style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date"  class="input-sm form-control" name="fin_arret">
                            </div>
                        </div>


                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listesconsultation') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-danger">
                                    Liste
                                </button>
                            </a>
							<a href="{{ route('detect_matricul') }}">
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

                {!!Form::close() !!}

            </div>
        </div>

    </div>

@endsection