@extends('erhselect')
@section('content')

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Recrutement</li>
                            <li class="breadcrumb-item active">Ajouter travailleur</li>
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
                            <h2>Etape 1 : Enregistrement du travailleur</h2>
							
							<?php
							
							/*echo "date du jour en français : " ;
							// selon le serveur c'est fr ou fr_FR ou fr_FR.ISO8859-1 qui est correct.
							setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
							echo strftime("%A %d %B %Y."); 
							
							/*$date1 = date('Y-m-d');
							$newDate_debut = strftime("%A %d %B %G", strtotime($date1));
							echo $newDate_debut ; 
							setlocale(LC_TIME, "fr_FR");
							echo strftime("%A %d %B %G", strtotime($newDate_debut));*/
							
							//setlocale(LC_TIME, 'fr_FR');
							//date_default_timezone_set('Europe/Paris');
							//echo utf8_encode(strftime('%A %d %B %Y, %H:%M'));*/
							
							?>
							
                        </div>
                
                        <form action="{{ url('post_travailleur') }}" method="POST" role="form" class="form-auth-small">
                        @csrf    
                        <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="matricule" class="control-label">Matricule</label>
                                            <input  required style="color: black; font-weight: bold" value="J000<?php echo intval($maj_big) + 1 ?>" placeholder="Le matricule" type="text" name="matricule" class="form-control text-uppercase" maxlength="50">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="nom" class="control-label">Nom</label>
                                            <input required placeholder="Nom" type="text" name="nom" class="form-control text-uppercase">
                                            <input type="hidden" value="{{ $maj_big }}" name="matri_quatre" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="prenom" class="control-label">Prénoms</label>
                                            <input required type="text" placeholder="Prénoms" name="prenom" class="form-control text-uppercase" maxlength="150">
                                        </div>
                                    </div>

                                   <!-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de d'embauche</label>
                                            <input required type="date" name="dateembauche" class="form-control">
                                        </div>
                                    </div> -->

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de naissance</label>
                                            <input required type="date" name="datenaissance" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="situation_mat" class="control-label">Situation matrimoniale</label>
                                            <select required name="situation_mat" class="form-control show-tick ms select2" data-placeholder="Select">
                                                <option value="">-DEROULER-</option>
                                                <option value="Celibataire">Célibataire</option>
                                                <option value="Marie">Marié(e)</option>
                                            </select>

                                        </div>
                                    </div>

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

                                    <div class="col-lg-4 col-md-6 col-sm-12">
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

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="paysid" class="control-label">Pays</label>
                                            <select required name="paysid" class="form-control select2-active">
                                                <option value="">-DEROULER-</option>
                                                @foreach($data_pays as $pys)
                                                <option value="{{ $pys->id }}">{{ $pys->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="idtype_contrat" class="control-label">Type Contrat</label>
                                            <select required name="idtype_contrat" id="idtype_contrat" class="form-control select2-active">
                                                <option value="">-DEROULER-</option>
                                                @foreach($data_typecontrat as $depart)
                                                <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="etapeid" class="control-label">Etape suivante ?</label>
                                            <select required name="etapeid" class="form-control select2-active">
                                                <option value="">-DEROULER-</option>
                                                <option value="1">Oui</option>
                                                <option value="0">Non</option>
                                            </select>
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-6 col-sm-12" id="newcontrat_embauche" style="display:none">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de d'embauche</label>
                                            <input type="date" name="date_embauche_contrat_new" class="form-control">
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-6 col-sm-12" id="newcontrat_embauche_journalier" style="display:none">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de d'embauche</label>
                                            <input type="date" value="{{ date('Y-m-d') }}" name="date_embauche_journalier" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12" id="newcontrat_fin" style="display:none">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de fin de contrat</label>
                                            <input type="date" name="date_fin_contrat_new" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="mot" class="control-label">Un Mot Sur Le Journalier</label>
                                           <textarea rows="3" class="form-control" name="mot"></textarea>
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

                        {!! html()->form()->close() !!}
                    </div>
                </div>
            </div>

@endsection