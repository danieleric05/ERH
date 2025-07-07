@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des travailleurs non déclarés
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des travailleurs non déclarés</li>
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
			
				
				<a href="{{ route('liste_travailleurs') }}" style="float: right; padding-left: 15px; padding-right: 15px" class="btn btn-dark m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Tous les travailleurs
                </a>
                <a href="{{ url('liste-embauches') }}" style="float: right; padding-left: 10px; padding-right: 10px" class="btn btn-success m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Travailleurs embauchés
                </a>
                <a href="{{ route('liste_cessations') }}" style="float: right; padding-left: 15px; padding-right: 15px" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Travailleurs en cessations
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Image</th>
                                <th>Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th>Date d'embauche</th>
                                <th>Date fin de contrat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_declarations as $listedata)
                                <tr>
                                    <td>
                                        @if(!$listedata->avatar)
                                            <img src="{{ asset('rhassets/images/images.png') }}" height="50" width="50" class="rounded-circle user-photo">
                                        @endif
                                        @if($listedata->avatar)
                                            <img src="{{ asset('rhassets/images/images.png') }}" height="50" width="50" class="rounded-circle user-photo">
                                        @endif
                                    </td>
                                    <td style="color: black; font-weight: bold">
                                        <a title="MODIFIER" style="color: red">
                                        {{ $listedata->matricule }}
                                        </a>
                                    </td>
                                    <td>{{ $listedata->nom.' '.$listedata->prenom }}</td>

                                    <td>{{ $listedata->date_debut_contrat }}</td>
                                    <td style="color: red; font-weight: bold">{{ $listedata->date_fin_contrat }}</td>
                                    
                                    <td class="actions text-center">
                                        <a title="AJOUTER SON NUMERO CNPS" target="_blank" href="{{ route('etapedeuxtravailleur', $listedata->id) }}" style="background-color: #9ad722" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove">
                                            <i class="icon-doc" aria-hidden="true"></i>
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

    </div>

@endsection