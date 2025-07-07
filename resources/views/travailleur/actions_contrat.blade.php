@extends('erhform')
@section('content')

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Recrutement</li>
                            <li class="breadcrumb-item active">Télecharger un contrat @if($edit->etapeid == 3) de CESSATION @endif @if($edit->etapeid == 4) de CERTIFICAT DE TRAVAIL @endif @if($edit->etapeid == 5) de DECLARATION CNPS @endif</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row clearfix">

                @include('success')
                @include('errors')

                <div class="col-lg-12">
                    <div class="card perso_color_title">
                        <div class="header">
                            <h2>Telecharger le contrat</h2>
                        </div>
                        <div class="body text-center perso_color">
                            <a href="{{ route('liste_tous_travailleurs') }}">
                                <button type="button" class="btn btn-outline-danger">Retour</button>
                            </a>
                            @if($edit->etapeid == 3)
                                <a title="CESSATION" href="{{ route('telechargerContratCessassion',['id'=>$edit->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-primary">TELECHARGER CONTRAT DE CESSATION</button>
                                </a>
                                <a title="PRECARITE" href="{{ route('telechargerFichePrecarite',['id'=>$edit->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-primary">TELECHARGER LA FICHE DE PRECARITE</button>
                                </a>
                            @endif
                            @if($edit->etapeid == 4)
                                <a target="_blank" title="CERTIFICAT DE TRAVAIL" href="{{ route('telechargerContratCertificatTravail',['id'=>$edit->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-secondary">TELECHARGER CERTIFICAT DE TRAVAIL</button>
                                </a>
                            @endif
                            @if($edit->etapeid == 5)
                                <a  target="_blank" title="DECLARATION CNPS" href="{{ route('telechargerContratDeclarationCnps',['id'=>$edit->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-success">TELECHARGER DECLARATION CNPS</button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

@endsection