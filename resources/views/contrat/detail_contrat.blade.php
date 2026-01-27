@extends('layouts.erh')
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
					<h4 class="m-t-5 text-light">{{ \App\Travailleur::where('id', $id)->first()->prenom }} {{ \App\Travailleur::where('id', $id)->first()->nom }}</h4>
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
						<p class="text-muted">{{ \App\Travailleur::where('id', $id)->first()->description }}</p>
					</div>
					<hr>
					<div class="row">
						<div class="col-6">
							<h5 style="font-size: 12px; font-weight: bold">
							
							@foreach($data_unites as $unite)
								@if($unite->id == \App\Travailleur::where('id', $id)->first()->uniteid)
									{{ $unite->label }}
								@endif
							@endforeach
							
							</h5>
							<small>Unité</small>
						</div>
						<div class="col-6">
							<h5 style="font-size: 11px; font-weight: bold">
							
								@foreach($data_equipes as $equipe)
									@if($equipe->id == \App\Travailleur::where('id', $id)->first()->equipeid)
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
									{{ \App\Travailleur::where('id', $info->travailleurid)->first()->matricule }}
									</td>
									<td>
									{{ \App\Travailleur::where('id', $info->travailleurid)->first()->nom }}
									</td>
									<td>
										{{ \App\Travailleur::where('id', $info->travailleurid)->first()->prenom }}
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
									{{ \App\Travailleur::where('id', $hist->travailleurid)->first()->matricule }}
									</td>
									<td>
									{{ \App\Travailleur::where('id', $hist->travailleurid)->first()->nom }}
									</td>
									<td>
										{{ \App\Travailleur::where('id', $hist->travailleurid)->first()->prenom }}
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
											{{ \App\Travailleur::where('id', $hist->travailleurid)->first()->matricule }}
											</td>
											<td>
											{{ \App\Travailleur::where('id', $hist->travailleurid)->first()->nom }} {{ \App\Travailleur::where('id', $hist->travailleurid)->first()->prenom }}
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


<script>
function tableSort() {
    return {
        sortBy: null,
        sortDir: 'asc',

        sort(column) {
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc';
            }
            this.sortTable();
        },

        sortTable() {
            const tbody = document.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const headers = document.querySelectorAll('thead th');
                let colIndex = 0;
                
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].textContent.toLowerCase().includes(this.sortBy.toLowerCase())) {
                        colIndex = i;
                        break;
                    }
                }

                const cellA = a.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                const cellB = b.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                
                if (!cellA || !cellB) return 0;

                let valueA = cellA.textContent.trim();
                let valueB = cellB.textContent.trim();

                const dateA = new Date(valueA).getTime();
                const dateB = new Date(valueB).getTime();

                if (!isNaN(dateA) && !isNaN(dateB) && dateA > 0 && dateB > 0) {
                    return this.sortDir === 'asc' ? dateA - dateB : dateB - dateA;
                }

                return this.sortDir === 'asc'
                    ? String(valueA).localeCompare(String(valueB), 'fr-FR')
                    : String(valueB).localeCompare(String(valueA), 'fr-FR');
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    }
}
</script>
@endsection