@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="#" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des variables </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Variables</li>
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
                    <div class="row clearfix">

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <a href="{{ route('listevariables_manuelle') }}">
                                <div class="card text-center bg-info">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <h3>0</h3>
                                            <span>Variables manuelles</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <a href="{{ route('listevariables_automatique') }}">
                                <div class="card text-center bg-secondary">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <h3>0</h3>
                                            <span>Variables automatique</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <a href="{{ route('listevariables_heure_supp') }}">
                                <div class="card text-center bg-warning">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <h3>0</h3>
                                            <span style="font-size: 13px">Heures supplementaires</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <a href="{{ route('listevariables_autres_variables') }}">
                                <div class="card text-center bg-danger">
                                    <div class="body">
                                        <div class="p-15 text-light">
                                            <h3>0</h3>
                                            <span>Autres variables</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection