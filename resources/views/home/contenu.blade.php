
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>
                    Bienvenue {{ Auth::user()->name }}
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active">Accueil</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">
            </div>
        </div>
    </div>

    @if((Auth::user()->idrole == 4) || (Auth::user()->idrole == 5))

        <div class="row clearfix">

            <div class="col-lg-12 col-md-12 text-center" style="background: #fff; padding-top: 60px; padding-bottom: 60px">

                <img src="{{ asset('rhassets/images/logoo.png') }}" width="600" height="300" alt="Logo-Plastica">

            </div>

        </div>

    @endif
	

    @if(Auth::user()->idrole == 6)
		
		<div class="col-sm-12">
            <marquee> <span style="color: red; font-weight: bold"> VOUS AUREZ {{ count($count_fin_contrat) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES  </span> </marquee>
        </div>
		
		<div class="alert alert-success text-center" style="font-weight: bold" role="alert">Mission et Stock tenue </div>

        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card" style="border: double #0000cc 2px">
                    <div class="header">
                        <h2>Mission <small>Liste des travailleurs ayant rempli la fiche de mission</small></h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled feeds_widget">
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Jaures <small class="float-right text-muted">12-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Yves <small class="float-right text-muted">25-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Itali</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Okou <small class="float-right text-muted">19-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Espagne</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Okou <small class="float-right text-muted">19-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Espagne</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <a href="#" class="text-right">Voir Plus</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card" style="border: double #d70206 2px">
                    <div class="header">
                        <h2>Stock tenues <small>Liste des articles ayant atteint le seuil</small> </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0 c_list">
                                <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>En stock</th>
                                    <th>Approvisionné le</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($article as $art)

                                    <tr>
                                        <td style="font-weight: bold; color: red">
                                            {{ $art?->label }}
                                        </td>
                                        <td style="font-weight: bold; color: red">
                                            {{ $art->quantite_en_stock }}
                                        </td>
                                        <td>
                                            {{ $art->date_reception }}
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                            <a href="{{ route('stock_tenues') }}" class="text-right">Voir Plus</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
	
	@endif
	
	@if(Auth::user()->idrole == 2)
		
		<div class="col-sm-12">
            <marquee> <span style="color: red; font-weight: bold"> VOUS AUREZ {{ count($count_fin_contrat) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES  </span> </marquee>
        </div>
		
        <div class="alert alert-danger text-center" style="font-weight: bold" role="alert">Journaliers et embauchés</div>

        <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-user"></i> </div>
                        <div class="content">
                            <div class="text">Nombre d'embauchés</div>
                            <h5 class="number">{{ $data_embauche }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-users"></i> </div>
                        <div class="content">
                            <div class="text">Nombre de journaliers</div>
                            <h5 class="number">{{ $data_journalier }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card top_counter">
                    <!--<div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Journalier enregistrés ce mois</div>
                            <h5 class="number">{{ $data_travailleur_moisencour }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <a href="{{ url('journaliers-fin-contrat') }}">
                            <div class="content">
                                <div class="text">Journalier en fin de contrat</div>
                                <h5 class="number">{{ count($count_fin_contrat) }}</h5>
                            </div>
                        </a>
                    </div>-->
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Journaliers non déclarés</div>
                            <h5 class="number">{{ count($count_journaler_non_declare) }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <a style="color: red; font-weight: bold" href="{{ url('journaliers-fin-contrat') }}">
                            <div class="content">
                                <div class="text">Journaliers en fin de contrat</div>
                                <h5 class="number">{{ count($count_fin_contrat) }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card top_counter">
                    <!--<div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Total Salary</div>
                            <h5 class="number">$2.8M</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Avg. Salary</div>
                            <h5 class="number">$1,250</h5>
                        </div>
                    </div>-->
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-user"></i> </div>
						<a style="color: red; font-weight: bold" href="{{ route('listesaccident') }}">
							<div class="content">
								<div class="text">Accident de travail</div>
								<h5 class="number">{{ $listeAT }}</h5>
							</div>
						</a>
                        <hr>
                        <div class="icon"><i class="fa fa-users"></i> </div>
                        <div class="content">
                            <div class="text">Consultations sans A.T / avec A.T</div>
                            <h5 class="number"> 
							<a href="{{ route('listesconsultation') }}">
								<button style="padding-left: 15px; padding-right: 15px; padding-top: 0px; padding-bottom: 0px;" type="button" class="btn btn-xs btn-danger">
								<span>{{ $data_sans_arret }}</span>
								</button>
							</a> 
							<a href="{{ route('listesconsultation') }}">
								<button style="padding-left: 20px; padding-right: 20px; padding-top: 0px; padding-bottom: 0px;" href="#" type="button" class="btn btn-xs btn-primary">
								<span>{{ $data_avec_arret }}</span>
								</button>
							</a> 
							</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
	
	@endif

    @if((Auth::user()->idrole == 1))

        <div class="col-sm-12">
            <marquee> <span style="color: red; font-weight: bold"> VOUS AUREZ {{ count($count_fin_contrat) }} JOURNALIERS EN FIN DE CONTRAT DANS 2 SEMAINES  </span> </marquee>
        </div>
		
        <div class="alert alert-danger text-center" style="font-weight: bold" role="alert">Journaliers et embauchés</div>

        <div class="row clearfix">
            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-user"></i> </div>
                        <div class="content">
                            <div class="text">Nombre d'embauchés</div>
                            <h5 class="number">{{ $data_embauche }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-users"></i> </div>
                        <div class="content">
                            <div class="text">Nombre de journaliers</div>
                            <h5 class="number">{{ $data_journalier }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card top_counter">
                    <!--<div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Journalier enregistrés ce mois</div>
                            <h5 class="number">{{ $data_travailleur_moisencour }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <a href="{{ url('journaliers-fin-contrat') }}">
                            <div class="content">
                                <div class="text">Journalier en fin de contrat</div>
                                <h5 class="number">{{ count($count_fin_contrat) }}</h5>
                            </div>
                        </a>
                    </div>-->
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Journaliers non déclarés</div>
                            <h5 class="number">{{ count($count_journaler_non_declare) }}</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <a style="color: red; font-weight: bold" href="{{ url('journaliers-fin-contrat') }}">
                            <div class="content">
                                <div class="text">Journaliers en fin de contrat</div>
                                <h5 class="number">{{ count($count_fin_contrat) }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card top_counter">
                    <!--<div class="body">
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Total Salary</div>
                            <h5 class="number">$2.8M</h5>
                        </div>
                        <hr>
                        <div class="icon"><i class="fa fa-university"></i> </div>
                        <div class="content">
                            <div class="text">Avg. Salary</div>
                            <h5 class="number">$1,250</h5>
                        </div>
                    </div>-->
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card top_counter">
                    <div class="body">
                        <div class="icon"><i class="fa fa-user"></i> </div>
						<a style="color: red; font-weight: bold" href="{{ route('listesaccident') }}">
							<div class="content">
								<div class="text">Accident de travail</div>
								<h5 class="number">{{ $listeAT }}</h5>
							</div>
						</a>
                        <hr>
                        <div class="icon"><i class="fa fa-users"></i> </div>
                        <div class="content">
                            <div class="text">Consultations sans A.T / avec A.T</div>
                            <h5 class="number"> 
							<a href="{{ route('listesconsultation') }}">
								<button style="padding-left: 15px; padding-right: 15px; padding-top: 0px; padding-bottom: 0px;" type="button" class="btn btn-xs btn-danger">
								<span>{{ $data_sans_arret }}</span>
								</button>
							</a> 
							<a href="{{ route('listesconsultation') }}">
								<button style="padding-left: 20px; padding-right: 20px; padding-top: 0px; padding-bottom: 0px;" href="#" type="button" class="btn btn-xs btn-primary">
								<span>{{ $data_avec_arret }}</span>
								</button>
							</a> 
							</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="alert alert-primary text-center" style="font-weight: bold" role="alert">Mois en cours</div>

        <div class="row">

            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <a href="{{ route('listesanctions') }}">
                    <div class="card tasks_report" style="border: double #000000 2px; background-color: #F2EFE3">
                        <div class="body">
                            <div class="text-center">
                                <img height="90" src="{{ asset('rhassets/images/images.png') }}" class="rounded-circle avatar" alt="">
                            </div>
                            <h6 class="m-t-20 text-dark">Gestion des Sanctions</h6>
                            <p class="displayblock m-b-0">{{ $data_sanctions }} <i class="zmdi zmdi-trending-up"></i></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <a href="{{ url('variables') }}">
                    <div class="card tasks_report"  style="border: double red 2px; background-color: #EFD6D5">
                        <div class="body">
                            <div class="text-center">
                                <img height="90" src="{{ asset('rhassets/images/images.png') }}" class="rounded-circle avatar" alt="">
                            </div>
                            <h6 class="m-t-20 text-danger">Gestion Variables</h6>
                            <p class="displayblock m-b-0">{{ $data_variables }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report" style="border: double #0000cc 2px; background-color: #e0eff5">
                    <div class="body">
                        <div class="text-center">
                            <img height="90" src="{{ asset('rhassets/images/images.png') }}" class="rounded-circle avatar" alt="">
                        </div>
                        <h6 class="m-t-20 text-primary">Gestion des autorisations</h6>
                        <p class="displayblock m-b-0">{{ $data_autorisation }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                <div class="card tasks_report" style="border: double green 2px; background-color: #C0E2BC">
                    <div class="body">
                        <div class="text-center">
                            <img height="90" src="{{ asset('rhassets/images/images.png') }}" class="rounded-circle avatar" alt="">
                        </div>
                        <h6 class="m-t-20 text-success">Précarité a payer</h6>
                        <p class="displayblock m-b-0">
                            <?php
                                $calcul = 0;
                                foreach ($data_precarite as $precarite){
                                    $calcul += 2768 + 176	* 0.03 * $precarite->valeur;
                                }
                            echo $calcul.' FCFA';

                            ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="alert alert-success text-center" style="font-weight: bold" role="alert">Mission et Stock tenue </div>

        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card" style="border: double #0000cc 2px">
                    <div class="header">
                        <h2>Mission <small>Liste des travailleurs ayant rempli la fiche de mission</small></h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled feeds_widget">
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Jaures <small class="float-right text-muted">12-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Yves <small class="float-right text-muted">25-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Itali</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Okou <small class="float-right text-muted">19-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Espagne</small>
                                    </div>
                                </a>
                            </li>
                            <li style="background-color: #fff">
                                <a href="#">
                                    <div class="feeds-left"><i class="fa fa-user"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title">Okou Okou <small class="float-right text-muted">19-04-2019 10:45</small></h4>
                                        <small>Pays/Villes : Dubai, France, Espagne</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <a href="#" class="text-right">Voir Plus</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card" style="border: double #d70206 2px">
                    <div class="header">
                        <h2>Stock tenues <small>Liste des articles ayant atteint le seuil</small> </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0 c_list">
                                <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>En stock</th>
                                    <th>Approvisionné le</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($article as $art)

                                    <tr>
                                        <td style="font-weight: bold; color: red">
                                            {{ $art?->label }}
                                        </td>
                                        <td style="font-weight: bold; color: red">
                                            {{ $art->quantite_en_stock }}
                                        </td>
                                        <td>
                                            {{ $art->date_reception }}
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                            <a href="{{ route('stock_tenues') }}" class="text-right">Voir Plus</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    @endif
