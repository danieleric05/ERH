@extends('erhselect')
@section('content')

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Recrutement</li>
                            <li class="breadcrumb-item active">Ajouter travailleur : Etape 2</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Etape 2 : Enregistrement</h2>
                        </div>
                        <form action="{{ url('post_edit_travailleur') }}" method="POST" role="form" class="form-auth-small">
                        @csrf
                        <div class="body">
                            <div class="row clearfix">

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone" class="control-label">Matricule</label>
                                        <input value="{{ $edit->matricule }}" readonly style="color: black; font-weight: bold; color: red" placeholder="Le matricule" type="text" name="matricule" class="form-control text-uppercase" maxlength="50">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone" class="control-label">Nom</label>
                                        <input value="{{ $edit->nom }}" style="color: black; font-weight: bold; color: red" placeholder="Nom" type="text" name="nom" class="form-control text-uppercase">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Prénoms</label>
                                        <input value="{{ $edit->prenom.$edit->prenom_suite }}" style="color: black; font-weight: bold; color: red" type="text" placeholder="Prénoms" name="prenom" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Date de d'embauche</label>
                                        <input value="{{ $edit->date_debut_contrat }}" style="font-weight: bold; color: red" type="date" name="dateembauche" class="form-control">
                                    </div>
                                </div>

                                @if($edit->type_employer == 1)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de fin de contrat</label>
                                            <input value="{{ $edit->date_fin_contrat }}" style="font-weight: bold; color: red" type="date" name="datefincontrat" class="form-control">
                                        </div>
                                    </div>
                                @endif

                                @if($edit->type_employer != 1)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="phone-ex" class="control-label">Date de fin de contrat</label>
                                            <input value="{{ $edit->date_fin_contrat }}" style="font-weight: bold; color: red" type="date" name="datefincontrat" class="form-control">
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Date de naissance</label>
                                        <input value="{{ $edit->date_naissance }}" style="font-weight: bold; color: red" type="date" name="datenaissance" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Situation matrimoniale</label>
                                        <select style="font-weight: bold; color: red" name="situation_mat" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option @if($edit->situation_mat == 'Célibataire') selected @endif value="Célibataire">Célibataire</option>
                                            <option @if($edit->situation_mat == 'Marié(e)') selected @endif value="Marie">Marié(e)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Unité</label>
                                        <select readonly="" style="font-weight: bold; color: red" name="uniteid"  class="form-control show-tick ms select2" data-placeholder="Select">
                                            @foreach($unites as $depar)
                                                <option @if($edit->uniteid == $depar->id) selected @endif value="{{ $depar->id }}">{{ $depar->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Departement</label>
                                        <select readonly="" style="font-weight: bold; color: red" name="departementid"  class="form-control show-tick ms select2" data-placeholder="Select">
                                            @foreach($departements as $depart)
                                                <option @if($edit->departementid == $depart->id) selected @endif value="{{ $depart->id }}">{{ $depart->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Equipe</label>
                                        <select readonly="" style="font-weight: bold; color: red" name="equipeid"  class="form-control show-tick ms select2" data-placeholder="Select">
                                            @foreach($equipes as $equi)
                                                <option @if($edit->equipeid == $equi->id) selected @endif value="{{ $equi->id }}">{{ $equi->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Type Contrat</label>
                                        <select style="font-weight: bold; color: red" name="idtype_contrat" class="form-control show-tick ms select2" data-placeholder="Select">
                                            @foreach($data_typecontrat as $contrat)
                                                <option @if($edit->idtype_contrat == $contrat->id) selected @endif value="{{ $contrat->id }}">{{ $contrat->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Pays</label>
                                        <select style="font-weight: bold; color: red" name="paysid" class="form-control show-tick ms select2" data-placeholder="Select">
                                            @foreach($pays as $pay)
                                                <option @if($edit->nationaliteid == $pay->id) selected @endif value="{{ $pay->id }}">{{ $pay->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!--<div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="typeEmployer" class="control-label">Type employé</label>
                                        <select style="color: black; font-weight: bold; color: red" required name="typeEmployer" class="form-control select2-active">
                                            <option @if($edit->type_employer == 1) selected @endif value="1">JOURNALIER</option>
                                            <option @if($edit->type_employer == 0) selected @endif value="0">PERMANENT</option>
                                        </select>
                                    </div>
                                </div>-->

                                <!--  NEW CHAMPS  -->
                                <div class="col-lg-4 col-md-6 col-sm-12">

                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">

                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">

                                </div>
                                <br/>
                                <br/>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="N° Pièce d’identité(ATT/CNI)" class="control-label">N° Pièce d’identité(ATT/CNI) </label>
                                        <input placeholder="N° Pièce d’identité(ATT/CNI)" value="{{ $edit->pieceidentite }}" style="color: black; font-weight: bold" type="text" name="pieceidentite" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Pièce d’identité Livrée le :</label>
                                        <input placeholder="Pièce d’identité Livrée le" value="{{ $edit->pieceidentite_livrele }}" style="color: black; font-weight: bold" type="date" name="pieceidentite_livrele" class="form-control" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Lieu d'acquisition de la pièce d’identité</label>
                                        <input placeholder="Lieu d'acquisition de la pièce d’identité" value="{{ $edit->pieceidentite_lieu }}" style="color: black; font-weight: bold" type="text" name="pieceidentite_lieu" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="lieunaissance" class="control-label">Lieu de naissance </label>
                                        <input placeholder="Lieu de naissance" value="{{ $edit->lieu_naissance }}" style="color: black; font-weight: bold" type="text" name="lieunaissance" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Email</label>
                                        <input placeholder="E-mail" value="{{ $edit->email }}" style="color: black; font-weight: bold" type="email" name="email" class="form-control" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="telephone" class="control-label">Téléphone</label>
                                        <input placeholder="Ex: 08080000" value="{{ $edit->telephone }}" style="color: black; font-weight: bold" type="number" name="telephone" class="form-control text-uppercase" maxlength="10">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="telephone2" class="control-label">Téléphone 2</label>
                                        <input placeholder="Ex: 08080000" value="{{ $edit->telephone2 }}" style="color: black; font-weight: bold" type="number" name="telephone2" class="form-control text-uppercase" maxlength="10">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="numero_securite" class="control-label">Numero de sécurite</label>
                                        <input placeholder="Numero de sécurite" value="{{ $edit->numero_securite }}" style="color: black; font-weight: bold" type="text" name="numero_securite" class="form-control text-uppercase" maxlength="12">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="bulletin_modele_salarie" class="control-label">Bulletin modèle du salarié</label>
                                        <input readonly placeholder="Bulletin modèle du salarié" value="{{ $edit->bulletin_modele_salarie }}" style="color: black; font-weight: bold" type="text" name="bulletin_modele_salarie" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="fonction_entrepriseid" class="control-label">Fonction occupée</label>
                                        <select style="color: black; font-weight: bold" name="fonction_entrepriseid" required class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option value="">-DEROULER-</option>
                                            @foreach($fonctions as $fonc)
                                                <option @if($edit->fonction_entrepriseid == $fonc->id) selected @endif value="{{ $fonc->id }}">{{ $fonc->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="communeid" class="control-label">Commune</label>
                                        <select style="color: black; font-weight: bold" name="communeid" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option value="">-DEROULER-</option>
                                            @foreach($commune as $comm)
                                                <option @if($edit->communeid == $comm->id) selected @endif value="{{ $comm->id }}">{{ $comm->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="categorieid" class="control-label">Categorie</label>
                                        <select style="color: black; font-weight: bold" name="categorieid" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option value="">-DEROULER-</option>
                                            @foreach($categories as $catego)
                                                <option @if($edit->categorieid == $catego->id) selected @endif value="{{ $catego->id }}">{{ $catego->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="nombre_enfant" class="control-label">Nombre d'enfants</label>
                                        <input placeholder="Nombre d'enfants" value="{{ $edit->nombre_enfant }}" style="color: black; font-weight: bold" type="number" name="nombre_enfant" class="form-control text-uppercase" maxlength="150">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="niveau_etudeid" class="control-label">Niveau d'etude</label>
                                        <select style="color: black; font-weight: bold" name="niveau_etudeid" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option value="">-DEROULER-</option>
                                            @foreach($niveauEtudes as $niveau)
                                                <option @if($edit->niveau_etudeid == $niveau->id) selected @endif value="{{ $niveau->id }}">{{ $niveau->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Civilité</label>
                                        <select style="font-weight: bold; color: black" name="civilite" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option value="">-DEROULER-</option>
                                            <option @if($edit->civilite == 'Monsieur') selected @endif value="Monsieur">Monsieur</option>
                                            <option @if($edit->civilite == 'Mademoiselle') selected @endif value="Mademoiselle">Mademoiselle</option>
                                            <option @if($edit->civilite == 'Madame') selected @endif value="Madame">Madame</option>
                                        </select>
                                    </div>
                                </div>
								
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="file_cni" class="control-label">CNI (facultatif)</label>
                                        <input style="color: black; font-weight: bold" type="file" name="file_cni" class="form-control text-uppercase">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="file_extrait_naiss" class="control-label">Extrait de naissance (facultatif)</label>
                                        <input style="color: black; font-weight: bold" type="file" name="file_extrait_naiss" class="form-control text-uppercase">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone-ex" class="control-label">Un Mot Sur Le Journalier(Savoir faire)</label>
                                       <textarea style="color: black; font-weight: bold" rows="3" class="form-control" name="mot">
                                           <?= $edit->description ?>
                                       </textarea>
                                    </div>
                                </div>



                                <div class="col-lg-12 col-md-6 col-sm-12 text-center">

                                    <button type="submit" class="btn btn-primary">Finaliser l'inscription</button>

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

                        </form>
                        
                    </div>
                </div>
            </div>

@endsection