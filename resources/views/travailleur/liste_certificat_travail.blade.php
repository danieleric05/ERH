@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des travailleurs ayant un certificat de travail actif
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des travailleurs ayant un certificat de travail actif</li>
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
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_certificat_travail as $listedata)
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
                                        <a title="MODIFIER" style="color: red" href="{{ route('etapedeuxtravailleur', $listedata->id) }}">
                                            {{ $listedata->matricule }}
                                        </a>
                                    </td>
                                    <td title="{{ $equipe = \App\Equipes::where('id', $listedata->equipeid)->first()->label }}">{{ $listedata->nom.' '.$listedata->prenom }}</td>

                                    <td>{{ $listedata->date_debut_contrat }}</td>
                                    <td style="color: red; font-weight: bold">{{ $listedata->date_fin_contrat }}</td>
                                    <td class="text-center" style="color: red; font-weight: bold">
                                        @if($listedata->etapeid == 3)
                                            <span class="badge badge-success">CESSASSION</span>
                                        @endif
                                        @if($listedata->etapeid == 4)
                                            <span class="badge badge-danger">CERTIFICAT DE TRAVAIL</span>
                                        @endif
                                    </td>
                                    <td class="actions text-center">

                                        @if($listedata->etapeid == 4)
                                            <a title="RECONDUIRE LE TRAVAILLEUR"  data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584" id="reconduire_journalier" data-id="{{ $listedata->id }}" href="{{ url('reconduire/journalier') }}" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
                                                <i class="icon-reload" aria-hidden="true"></i>
                                            </a>
                                        @endif

                                        <a title="TELECHARGER LE CONTRAT" target="_blank" href="{{ route('telechargerContratCertificatTravail',['id'=>$listedata->id, 'download'=>'pdf']) }}" style="background-color: #9ad717" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove">
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

    @include('travailleur.modal_declaration')
    @include('travailleur.modal_reconduire')

@endsection