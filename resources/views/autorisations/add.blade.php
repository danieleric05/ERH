@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="#" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des autorisations </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter une autorisation</li>
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

                <form action="{{ url('post_autorisation') }}" method="POST" role="form" class="form-auth-small">
                @csrf
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="demandeurid" class="control-label">Demandeur</label>
                                <select required name="demandeurid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="statut" class="control-label">Statut</label>
                                <select required name="statut" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">DIRECTEUR</option>
                                    <option value="2">CADRE</option>
                                    <option value="3">AGENT DE MAITRISE</option>
                                    <option value="4">OUVRIER</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="motif_absence" class="control-label">Motif de l'absense</label>
                                <select required name="motif_absence" id="motif_absence" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">MALADIE</option>
                                    <option value="2">CONVENANCE PERSONNELLE</option>
                                    <option value="3">PERMISSIONS EXCEPTIONNELLES</option>
                                    <option value="4">CONGES PAYES</option>
                                    <option value="5">CONGES SANS SOLDE</option>
                                    <option value="6">MISSION</option>
                                    <option value="7">AUTRES CAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Date debut</label>
                            <div class="input-group mb-3">
                                <input required name="date_debut" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Date de fin</label>
                            <div class="input-group mb-3">
                                <input required name="date_fin" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Date de reprise</label>
                            <div class="input-group mb-3">
                                <input required name="date_reprise" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Samedi/Dimanche</label>
                            <div class="input-group mb-3">
                                <select required name="weekend" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">OUI</option>
                                    <option value="2">NON</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="interim_assurer_par" class="control-label">Intérim assuré par</label>
                                <select name="interim_assurer_par" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_travailleur as $trav)
                                        <option value="{{ $trav->id }}">{{ $trav->nom.' '.$trav->prenom.' '.$trav->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6" style="display: none" id="hdebut">
                            <div class="form-group">
                                <label for="interim_assurer_par" class="control-label">Heure début</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-clock"></i></span>
                                    </div>
                                    <input type="time" name="heure_debut" class="form-control time24" placeholder="Ex: 23:59">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6" style="display: none" id="hfin">
                            <div class="form-group">
                                <label for="interim_assurer_par" class="control-label">Heure de fin</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-clock"></i></span>
                                    </div>
                                    <input type="time" name="heure_fin" class="form-control time24" placeholder="Ex: 23:59">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <label>Commentaire</label>
                            <div class="input-group mb-3">
                                <textarea rows="3" name="commentaire" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row" style="margin-right: 2px; margin-left: 2px; display: none" id="mission">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="continent" class="control-label">Continent</label>
                                    <select name="continent" class="form-control show-tick ms select2" data-placeholder="Select">
                                        <option value="">-DEROULER-</option>
                                        <option value="1">AFRIQUE</option>
                                        <option value="2">ASIE</option>
                                        <option value="3">AMERIQUE</option>
                                        <option value="4">EUROPE</option>
                                        <option value="5">AUSTRALIE</option>
                                    </select>
                                </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="pays" class="control-label">Pays</label>
                                <input class="form-control" name="pays" type="text">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="ville" class="control-label">Ville</label>
                                <input class="form-control" name="ville" type="text">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12" style="display: none">
                            <label>Nuitée</label>
                            <div class="input-group mb-3">
                                <input style="font-weight: bold; color: black" name="nuitee" readonly type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12" style="display: none">
                            <label>Journée</label>
                            <div class="input-group mb-3">
                                <input style="font-weight: bold; color: black" readonly name="journee" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="mode_transport" class="control-label">Mode de transport</label>
                                <select name="mode_transport" id="mode_transport" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">AVION</option>
                                    <option value="2">BATEAU</option>
                                    <option value="3">TRAIN</option>
                                    <option value="4">VEHICULE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="divers" class="control-label">Divers</label>
                                <select name="divers" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="5000">5000 FCFA</option>
                                    <option value="10000">10 000 FCFA</option>
                                    <option value="15000">15 000 FCFA</option>
                                    <option value="20000">20 000 FCFA</option>
                                    <option value="25000">25 000 FCFA</option>
                                    <option value="30000">30 000 FCFA</option>
                                    <option value="40000">40 000 FCFA</option>
                                    <option value="50000">50 000 FCFA</option>
                                    <option value="75000">75 000 FCFA</option>
                                    <option value="100000">100 000 FCFA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Achat mission</label>
                            <div class="input-group mb-3">
                                <input style="font-weight: bold; color: black" name="achat_mission" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Participation évènement</label>
                            <div class="input-group mb-3">
                                <input style="font-weight: bold; color: black" name="part_eve" type="text" class="form-control">
                            </div>
                        </div>
                    </div>

                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listeautorisations') }}">
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