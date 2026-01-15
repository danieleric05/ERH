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
                            @if($edit_travailleur->idtype_contrat == 2)
                                {{-- CDD --}}
                                <a target="_blank" title="CONTRAT CDD" href="{{ route('telechargerContratCDD',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-primary">TELECHARGER CONTRAT CDD</button>
                                </a>
                            @elseif($edit_travailleur->idtype_contrat == 3)
                                {{-- CDI --}}
                                <a target="_blank" title="CONTRAT CDI" href="{{ route('telechargerContratCDI',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-primary">TELECHARGER CONTRAT CDI</button>
                                </a>
                            @else
                                {{-- Journalier (default) --}}
                                <a target="_blank" title="CONTRAT JOURNALIER" href="{{ route('telechargerContratJournalier',['id'=>$edit_travailleur->id, 'download'=>'pdf']) }}">
                                    <button type="button" class="btn btn-outline-primary">TELECHARGER CONTRAT JOURNALIER</button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

@endsection