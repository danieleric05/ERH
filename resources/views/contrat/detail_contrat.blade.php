@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Détail du contrat
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Contrat</li>
                    <li class="breadcrumb-item active">Détail du contrat</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

		<div class="col-lg-4 col-md-12">
		</div>
		<div class="col-lg-4 col-md-12">
			<div class="card member-card">
				<div class="header bg-info">
					<h4 class="m-t-5 text-light">{{ optional(\App\Travailleur::where('id', $id)->first())->prenom }} {{ optional(\App\Travailleur::where('id', $id)->first())->nom }}</h4>
					<br/>
				</div>
				<div class="member-img">
					<a href="javascript:void(0);"><img src="{{ asset('rhassets/images/images.png') }}" class="rounded-circle" alt="profile-image"></a>
				</div>
				<div class="body">
					<div class="col-12">
						<ul class="social-links list-unstyled">
							<li><a title="facebook" href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
							<li><a title="twitter" href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
							<li><a title="instagram" href="javascript:void(0);"><i class="fa fa-instagram"></i></a></li>
						</ul>
						<p class="text-muted">{{ optional(\App\Travailleur::where('id', $id)->first())->description }}</p>
					</div>
					<hr>
					<div class="row">
						<div class="col-6">
							<h5 style="font-size: 12px; font-weight: bold">
							
							@foreach($data_unites as $unite)
								@if($unite->id == optional(\App\Travailleur::where('id', $id)->first())->uniteid)
									{{ $unite->label }}
								@endif
							@endforeach
							
							</h5>
							<small>Unité</small>
						</div>
						<div class="col-6">
							<h5 style="font-size: 11px; font-weight: bold">
							
								@foreach($data_equipes as $equipe)
									@if($equipe->id == optional(\App\Travailleur::where('id', $id)->first())->equipeid)
										{{ $equipe->label }}
									@endif
								@endforeach
							
							</h5>
							<small>Equipe</small>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="col-lg-4 col-md-12">
		</div>
		
		<div class="col-md-12 col-lg-12">
			<div class="card">
				<div class="header">
					<h2>Période des contrats</h2>                            
				</div>
				<div class="body">
					<div class="table-responsive social_media_table">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th>Action</th>
									<th>Matricule</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th class="text-center">Date de debut de contrat</th>
									<th class="text-center">Date de fin de contrat</th>
									<th class="text-center">Télecharger</th>
								</tr>
							</thead>
							<tbody>
							
								@foreach($infoContrat as $info)
							
								<tr>
									<td>
										@if($info->actionid == 3)
											<span class="badge badge-dark">Cessation</span>
										@endif
										@if($info->actionid == 4 )
											<span class="badge badge-dark">Certificat</span>
										@endif
										@if($info->actionid == 6 )
											<span class="badge badge-dark">Reconduire</span>
										@endif
										@if($info->actionid == 1 )
											<span class="badge badge-dark">Premier Contrat</span>
										@endif
									</td>
									<td>
									{{ optional(\App\Travailleur::where('id', $info->travailleurid)->first())->matricule }}
									</td>
									<td>
									{{ optional(\App\Travailleur::where('id', $info->travailleurid)->first())->nom }}
									</td>
									<td>
										{{ optional(\App\Travailleur::where('id', $info->travailleurid)->first())->prenom }}
									</td>
									<td class="text-center">
										<span class="badge badge-primary">{{ $info->debut_contrat }}</span>
									</td>
									<td class="text-center">
										<span class="badge badge-danger">{{ $info->fin_contrat }}</span>
									</td>
									<td class="text-center">
										<a title="TELECHARGER LE CONTRAT" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
											<i style="color: #fff" class="icon-doc" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								
								@endforeach
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6 col-lg-6">
			<div class="card">
				<div class="header">
					<h2>Changement d'unité </h2>                            
				</div>
				<div class="body">
					<div class="table-responsive social_media_table">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th>Matricule</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th class="text-center">Unité</th>
									<th class="text-center">Date d'arrivée</th>
								</tr>
							</thead>
							<tbody>
							
								@foreach($infoHistUnite as $hist)
								<tr>
									<td>
									{{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->matricule }}
									</td>
									<td>
									{{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->nom }}
									</td>
									<td>
										{{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->prenom }}
									</td>
									<td class="text-center">
										<span class="badge badge-primary">{{ $hist->date_choix }}</span>
									</td>
									<td class="text-center">
										<span class="badge badge-danger">{{ $hist->date_choix }}</span>
									</td>
								</tr>
								
								@endforeach
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6 col-lg-6">
			<div class="card">
				<div class="header">
					<h2>Gestion des tenues </h2>                            
				</div>
				<div class="body">
					<div class="table-responsive social_media_table">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th>Matricule</th>
									<th>Nom</th>
									<th class="text-center">Tenues reçu</th>
									<th class="text-center">Reçu le</th>
								</tr>
							</thead>
							
									@foreach($infoTenues as $hist)
									<tbody>
										<tr>
											<td>
											{{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->matricule }}
											</td>
											<td>
											{{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->nom }} {{ optional(\App\Travailleur::where('id', $hist->travailleurid)->first())->prenom }}
											</td>
											
											<td class="text-center">
												<span class="badge badge-primary">{{ $hist->date_choix }}</span>
											</td>
											<td class="text-center">
												<span class="badge badge-danger">{{ $hist->date_choix }}</span>
											</td>
										</tr>
									</tbody>
									@endforeach
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

    </div>

@endsection