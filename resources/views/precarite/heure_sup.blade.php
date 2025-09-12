@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="#" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des variables(heure supplémentaire) </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter une variable(heure supplémentaire)</li>
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

                <form action="{{ url('post_variables') }}" method="POST" role="form" class="form-auth-small">
                @csrf
                <div class="body">

                    <div class="row clearfix">

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="travailleurid" class="control-label">Employé(s)</label>
                                <select name="travailleurid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombre d'heure</label>
                                <select name="motif" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">1 Heure</option>
                                    <option value="2">2 Heures</option>
                                    <option value="3">3 Heures</option>
                                    <option value="4">4 Heures</option>
                                    <option value="5">5 Heures</option>
                                    <option value="6">6 Heures</option>
                                    <option value="7">7 Heures</option>
                                    <option value="8">8 Heures</option>
                                    <option value="9">9 Heures</option>
                                    <option value="10">10 Heures</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Date</label>
                            <div class="input-group mb-3">
                                <input name="date_heure_supp" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="justification" class="control-label">Justification</label>
                                <textarea rows="3" class="form-control" name="justification"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listevariables_heure_supp') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-danger">
                                    Liste
                                </button>
                            </a>
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

                </div>

                </form>     {!!Form::close() !!}

            </div>
        </div>

    </div>

@endsection