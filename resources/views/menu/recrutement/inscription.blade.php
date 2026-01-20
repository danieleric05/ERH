@extends('layouts.erh')
@section('content')

    <section class="admin-content" data-select2-id="12">
        <div class="bg-dark">
            <div class="container  m-b-30">
                <div class="row">
                    <div class="col-12 text-white p-t-40 p-b-90">
                    </div>
                </div>
            </div>
        </div>

        <div class="container  pull-up" data-select2-id="11">
            <div class="row" data-select2-id="10">
                <div class="col-lg-12" data-select2-id="9">
                    <div class="card m-b-30">
                        <div class="card-header">
                            <h5 class="m-b-0"> Inscription d'un travailleur </h5>
                        </div>
                        <div class="card-body">

                            <form method="post">

                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Matricule</label>
                                            <input required type="text" name="matricule" class="form-control text-uppercase" placeholder="Matricule" autocomplete="off" maxlength="15">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Nom</label>
                                            <input required type="text" name="nom" class="form-control text-uppercase" placeholder="Le nom svp" autocomplete="off" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Prénoms</label>
                                            <input required type="text" name="prenoms" class="form-control text-uppercase" placeholder="Votre prénoms svp" autocomplete="off" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Date d'embauche</label>
                                            <input required type="text" class="js-datepicker form-control" placeholder="Choisir la date">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Date de naissance</label>
                                            <input required type="text" class="js-datepicker form-control" placeholder="Choisir la date">
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Situation Familiale</label>
                                            <select class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                                <option value="0">Célibataire</option>
                                                <option value="1">Marié(e)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Nombre d'enfants</label>
                                            <select class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Nationalité</label>
                                            <select class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Télephone</label>
                                            <input type="text" name="tel" class="form-control" placeholder="Le numéro de téléphone svp" autocomplete="off" maxlength="8">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Departement</label>
                                            <select id="iddepartement" class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Service(Equipe)</label>
                                            <select id="idequipe" class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Niveau d'etude</label>
                                            <select id="idequipe" class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">Diplome obtenu</label>
                                            <select id="idequipe" class="form-control js-select2" style="width: 335px">
                                                <option value="">-DEROULER-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Un mot sur l'embauché</label>
                                            <textarea class="form-control" id="mot" name="mot" rows="5"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <br/>
                                <div class="form-group text-center">
                                    <button class="btn btn-danger">Enregistrer les informations</button>
                                </div>
                                <br/>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection