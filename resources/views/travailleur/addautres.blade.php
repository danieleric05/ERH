@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Ajouter travailleur(non inscrit dans la base)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">

                @include('success')
                @include('errors')

                <div class="header">
                    <h2>Enregistrement</h2>
                </div>
                {!!Form::open (['url'=>['post_travailleur_autres'], 'method'=>'post', 'role'=>'form'])!!}
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Matricule</label>
                                <input  required style="color: black; font-weight: bold" value="A000" placeholder="Le matricule" type="text" name="matricule" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="nom" class="control-label">Nom</label>
                                <input required placeholder="Nom" type="text" name="nom" class="form-control text-uppercase">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Prénoms</label>
                                <input required type="text" placeholder="Prénoms" name="prenom" class="form-control text-uppercase" maxlength="150">
                            </div>
                        </div>

                        <!--<div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Date d'embauche</label>
                                <input required type="date" name="dateembauche" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Date de fin de contrat</label>
                                <input required type="date" name="dateembauche" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Situation matrimoniale</label>
                                <select required name="situation_mat" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="Celibataire">Célibataire</option>
                                    <option value="Marie">Marié(e)</option>
                                </select>

                            </div>
                        </div>-->

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Unité</label>
                                <select required name="uniteid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_unites as $date)
                                        <option value="{{ $date->id }}">{{ $date->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone-ex" class="control-label">Departement</label>
                                <select required name="departementid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_departements as $depart)
                                        <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                       <!-- <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="equipeid" class="control-label">Equipe</label>
                                <select required name="equipeid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_equipes as $depart)
                                        <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        -->

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="fonction_entrepriseid" class="control-label">Fonction occupée</label>
                                <select style="color: black; font-weight: bold" name="fonction_entrepriseid" required class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_fonctions as $fonc)
                                        <option value="{{ $fonc->id }}">{{ $fonc->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="telephone" class="control-label">Téléphone</label>
                                <input placeholder="Ex: 08080000" style="color: black; font-weight: bold" type="number" name="telephone" class="form-control text-uppercase" maxlength="10">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="paysid" class="control-label">Pays</label>
                                <select required name="paysid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_pays as $depart)
                                        <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 col-sm-12 text-center">

                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listetravailleurs') }}">
                                <button style="padding-left: 35px; padding-right: 35px" href="#" type="button" class="btn btn-danger">
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