@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="#" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des congés </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter un congé</li>
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

                <form action="{{ url('post_conges') }}" method="POST" role="form">
    @csrf

                <div class="body">
                    <div class="row clearfix">

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="travailleurid" class="control-label">Travailleur</label>
                                <select required name="travailleurid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="type_conge" class="control-label">Type de congé</label>
                                <select required name="type_conge" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">CONGE ANNUEL</option>
                                    <option value="2">CONGE MALADIE</option>
                                    <option value="3">CONGE MATERNITE/PATERNITE</option>
                                    <option value="4">CONGE SANS SOLDE</option>
                                    <option value="5">CONGE EXCEPTIONNEL</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Date début</label>
                            <div class="input-group mb-3">
                                <input required name="debut" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Date de fin</label>
                            <div class="input-group mb-3">
                                <input required name="fin" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Date de reprise</label>
                            <div class="input-group mb-3">
                                <input required name="date_reprise" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
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
                            <a href="{{ route('listeconges') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-danger">
                                    Liste
                                </button>
                            </a>
                            <a href="{{ route('calendrier_conges') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-info">
                                    Calendrier
                                </button>
                            </a>
                        </div>

                    </div>
                </div>

                </form>

            </div>
        </div>

    </div>

@endsection
