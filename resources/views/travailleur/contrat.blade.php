@extends('erhform')
@section('content')

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Recrutement</li>
                            <li class="breadcrumb-item active">Télecharger un contrat</li>
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
                            <h2> Etape 3 :  Telecharger le contrat</h2>
                        </div>
                        <div class="body text-center perso_color">
                            <a href="{{ route('liste_tous_travailleurs') }}">
                                <button type="button" class="btn btn-outline-danger">Retour</button>
                            </a>
                            <a target="_blank" title="CONTRAT" href="{{ route('telechargerContratJournalier',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}">
                                <button type="button" class="btn btn-outline-primary">TELECHARGER CONTRAT</button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

@endsection