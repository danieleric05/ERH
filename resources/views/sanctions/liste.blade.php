@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestions des sanctions
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Sanctions</li>
                    <li class="breadcrumb-item active">Liste des sanctions</li>
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

                <a href="{{ url('ajouter-sanction') }}" style="float: right" class="btn btn-info m-b-15 m-t-10 m-r-20">
                    <i class="icon-plus" aria-hidden="true"></i> Ajouter
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Demandeur</th>
                                <th class="text-center">Fautif(s)</th>
                                <th>Motif</th>
                                <th>Sanction</th>
                                <th>Resultat</th>
                                <th class="text-center">Etat</th>
                                <th>Date sanction</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>
                            @foreach($Sanctions as $vari)
                            <tr>
                                <td title="{{ $trava = \App\Travailleur::where('id', $vari->demandeurid )->first()->matricule }}">
                                    {{ $trava = \App\Travailleur::where('id', $vari->demandeurid )->first()->nom }} <br/> {{ $trava = \App\Travailleur::where('id', $vari->demandeurid )->first()->prenom }}
                                </td>
                                <td>
                                    @foreach( unserialize($vari->employeid) as $servaiable )
                                        <span title="{{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->nom }} {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                            {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->matricule }}
                                        </span> <br/>
                                    @endforeach
                                </td>
                                <td>
                                    @if($vari->motif == 1)
                                        ABSENCE INJUSTIFIEE
                                    @endif
                                    @if($vari->motif == 2)
                                            INSUBORDINATION
                                    @endif
                                    @if($vari->motif == 3)
                                            RETARD REPETITIF
                                    @endif
                                    @if($vari->motif == 4)
                                            FAUTE LOURDE
                                    @endif
                                    @if($vari->motif == 5)
                                            INSUFFISANCE DE RENDEMENT
                                    @endif
                                    @if($vari->motif == 6)
                                            NEGLIGENCE PROFESSIONNELLE
                                    @endif
                                </td>
                                <td title="{{ $vari->expose_motif }}">
                                    @if($vari->sanction_applique == 1)
                                        AVERTISSEMENT
                                    @endif
                                    @if($vari->sanction_applique == 2)
                                            MISE A PIED
                                    @endif
                                    @if($vari->sanction_applique == 3)
                                            LICENCIEMENT
                                    @endif
                                </td>
                                <td title="@if($vari->sanction_applique == 2) NOMBRE DE JOURS @endif">
                                    @if($vari->sanction_applique == 1)
                                        AVERTISSEMENT
                                    @endif
                                    @if($vari->sanction_applique == 2)
                                        {{ $vari->nombre_jour }}
                                    @endif
                                    @if($vari->sanction_applique == 3)
                                        LICENCIEMENT
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">C.S</span><br/>
                                    <span class="badge badge-dark">D.U</span><br/>
                                    <span class="badge badge-danger">D.R.H</span>
                                </td>
                                <td>
                                    {{ $vari->datesanction }}
                                </td>
                                <td>
                                    <!--<a title="MODIFIER" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" href="#" data-toggle="tooltip" data-original-title="Remove">
                                        <i class="icon-pencil" aria-hidden="true"></i>
                                    </a>-->
                                    <a title="TELECHARGER" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" href="{{ route('techarger_sanctions', $vari->id) }}" data-toggle="tooltip" data-original-title="Remove">
                                        <i class="icon-reload" aria-hidden="true"></i>
                                    </a>
                                    <a @if($vari->statutid == 1) title="AJOUTER AUX VARIABLES" @endif @if($vari->statutid == 2) title="SANCTION DEJA AJOUTEE AUX VARIABLES" @endif  class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove" @if($vari->statutid == 1) href="{{ route('sanctionvariable', $vari->id) }}" @endif data-toggle="tooltip">
                                        <i class="icon-bag" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('tenues.modal_edit')

@endsection