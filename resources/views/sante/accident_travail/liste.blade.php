@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des accidents de travail
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des accidents de travail</li>
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
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Travailleur</th>
                                <th>Cause</th>
                                <th>Prescription</th>
                                <th class="text-center">Arret travail</th>
                                <th>Date</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($listeAT as $listedata)
                                <tr>

                                    <td title="{{ $matr = \App\Travailleur::where('id', $listedata->travailleurid)->first()->matricule }}">{{ $travail = \App\Travailleur::where('id', $listedata->travailleurid)->first()->nom }} {{ $prenom = \App\Travailleur::where('id', $listedata->travailleurid)->first()->prenom }}</td>
                                    <td>{{ $listedata->cause }}</td>
                                    <td>{{ $listedata->prescription }}</td>
                                    <td>
                                        <span class="badge badge-danger">DEBUT : {{ $listedata->debut_arret }}</span>
                                        <span class="badge badge-success">FIN : {{ $listedata->fin_arret }}</span>
                                    </td>

                                    <td>{{ $listedata->datepub }}</td>

                                    <td class="actions text-center">
										@if( (Auth::user()->idrole == 1) && ($listedata->statutid == 2) )
											<span class="badge badge-success">TRATER / le {{ $listedata->updated_at }}</span>
										@endif	
									
										@if( ( (Auth::user()->idrole == 1) || (Auth::user()->idrole == 2) ) && ($listedata->statutid == 1))
											<a href="{{ route('accident_travail_traiter', $listedata->id ) }}" title="CLIQUEZ POUR SIGNALER QU'IL A ETE VU ET TRATER"  data-toggle="tooltip" data-original-title="Remove" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
												<i class="icon-reload" aria-hidden="true"></i>
											</a>
										@endif	
									
										@if( ( (Auth::user()->idrole == 1) || (Auth::user()->idrole == 2) ) && ($listedata->statutid == 2) )
											<span class="badge badge-success">TRATER / le {{ $listedata->updated_at }}</span>
										@endif
										
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('sante.accident_travail.modal_edit')

@endsection