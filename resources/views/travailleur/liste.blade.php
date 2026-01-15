@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des travailleurs à étape deux
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des travailleurs à étape deux</li>
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

                <a href="{{ url('ajouter-travailleur-etape-un') }}" style="float: right" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Ajouter un travailleur
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Image</th>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Equipe</th>
                                <th>Date d'embauche</th>
                                <th>Date fin de contrat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_travailleurdeux as $listedata)
                                <tr>
                                    <td>
                                        @if(!$listedata->avatar)
                                            <img src="{{ asset('rhassets/images/images.png') }}" height="50" width="50" class="rounded-circle user-photo">
                                        @endif
                                        @if($listedata->avatar)
                                                <img src="{{ asset('rhassets/images/images.png') }}" height="50" width="50" class="rounded-circle user-photo">
                                        @endif
                                    </td>
                                    <td style="color: black; font-weight: bold">{{ $listedata->matricule }}</td>
                                    <td>{{ $listedata->nom }}</td>
                                    <td>{{ $listedata->prenom }}</td>
                                    <td style="color: black; font-weight: bold">
                                        {{ $equipe = \App\Equipes::where('id', $listedata->equipeid)->first()?->label }}
                                    </td>
                                    <td>{{ $listedata->date_debut_contrat }}</td>
                                    <td style="color: red; font-weight: bold">{{ $listedata->date_fin_contrat }}</td>
                                    <td class="actions text-center">
                                        <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"  href="{{ url('delete/categories/data') }}"  data-id="{{ $listedata->id }}" id="deletePays" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="icon-trash" aria-hidden="true"></i>
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

    @include('configuration.niveauEtude.modal_niveauEtude')

@endsection