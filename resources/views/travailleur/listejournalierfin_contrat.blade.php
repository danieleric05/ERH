@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des cessations
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des journaliers en fin de contrat</li>
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

                <a href="{{ route('liste_tous_travailleurs') }}" style="float: right" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Retour à la liste des journaliers
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
                                <th class="text-center">Etat</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_travailleur as $listedata)
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
                                    <td title="#">{{ $listedata->nom.' '.$listedata->prenom }}</td>

                                    <td>{{ $listedata->date_debut_contrat }}</td>
                                    <td style="color: red; font-weight: bold">{{ $listedata->date_fin_contrat }}</td>
                                    <td class="text-center" style="color: red; font-weight: bold">
                                        @if($listedata->etapeid == 3)
                                            <span class="badge badge-success">CESSASSION</span>
                                        @endif
                                        @if($listedata->etapeid == 4)
                                            <span class="badge badge-danger">CERTIFICAT DE TRAVAIL</span>
                                        @endif
                                        @if($listedata->etapeid == 5)
                                            <span class="badge badge-default">DECLARATION CNPS</span>
                                        @endif
                                        @if($listedata->etapeid == 6)
                                            <span class="badge badge-primary">RECONDUIRE</span>
                                        @endif
                                    </td>
                                    <td class="actions text-center">

                                        @if($listedata->etapeid != 3)
                                        <a title="CESSASSION/CERTIFICAT DE TRAVAIL/DECLARATION"  data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584" id="declaration_new" data-id="{{ $listedata->id }}" href="{{ url('action/declaration/travailleurs') }}" class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove">
                                            <i class="icon-map" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if($listedata->etapeid == 3)
                                            <a title="RECONDUIRE LE TRAVAILLEUR"  data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584" id="reconduire_journalier" data-id="{{ $listedata->id }}" href="{{ url('reconduire/journalier') }}" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
                                                <i class="icon-envelope" aria-hidden="true"></i>
                                            </a>
                                        @endif

                                        <a title="TELECHARGER LE CONTRAT" target="_blank" href="{{ route('telechargerContratCessassion',['id'=>$listedata->id, 'download'=>'pdf']) }}" class="btn btn-sm btn-danger btn-icon btn-pure on-default button-remove">
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

            <a href="{{ route('excel_download_fin_contrat') }}">
                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-success">
                    <i class="icon-folder" aria-hidden="true"></i> <b>TELECHARGER</b>
                </button>
            </a>

        </div>

    </div>

    @include('travailleur.modal_reconduire_fin')
    @include('travailleur.modal_declaration_fin')

@endsection