@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Historiques
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">HA01</li>
                    <li class="breadcrumb-item active">Historiques des HA01</li>
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

                    <form action="{{ url('post_search_histo_precarite') }}" method="POST" role="form" class="form-auth-small">
                    @csrf
                        <div class="row clearfix">

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="variables_type" class="control-label">Type</label>
                                <select required name="variables_type" class="form-control select2-active">
                                    <option value="">-DEROULER-</option>
                                    <option selected value="1">JOURNALIER</option>
                                    <option value="2">EMBAUCHE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="beginn" class="control-label">Début</label>
                                <input required type="date"  value="<?= date('Y-m-d')?>" name="beginn" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="endd" class="control-label">Fin</label>
                                <input required type="date" value="<?= date('Y-m-d')?>" max="<?= date('Y-m-d') ?>" name="endd" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12" style="margin-top: 28px">
                            <button type="submit" class="btn btn-danger" style="padding-left: 70px; font-weight: bold; font-size: 14px; padding-right: 70px; padding-top: 7px; padding-bottom: 6px">
                                Rechercher
                            </button>
                        </div>

                    </div>
                    </form>     {!!Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>N°</th>
                                <th>Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th >Nombre de jour</th>
                                <th class="text-center">Etat</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( (!($recherches)) && (!($variables)))
                                    AUCUNE DONNÉE DISPONIBLE
                            @endif
                            @if($recherches)
                                <?php $i=1; ?>
                                @foreach($recherches as $rech)
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $rech->matricule ?></td>
                                        @if($rech->matricule)
                                            <td>{{ $trava = \App\Travailleur::where('matricule', $rech->matricule )->first()->nom }} {{ $trava = \App\Travailleur::where('matricule', $rech->matricule )->first()->prenom }}</td>
                                        @endif
                                        <td><?= $rech->valeur ?></td>
                                        <td class="text-center">
                                            @if($rech->statutid == 2)
                                                <span class="badge badge-danger">Non Payé</span>
                                            @endif
                                            @if($rech->statutid == 3)
                                                <span class="badge badge-primary">Payé</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>

                                <td class="text-center" style="font-weight: bold; color: red">MONTANT A PAYER :
                                    <?php
                                    $ttHa = 0;
                                    $Apayer = 0;
                                    foreach($recherches as $reching){
                                        if($reching->statutid != 3){
                                            $ttHa += $reching->valeur;
                                            $Apayer += 2768 + (176*0.03*$ttHa);
                                        }
                                    }

                                    echo number_format($Apayer, '2', ',', '.');
                                    ?>
                                </td>

                                    <td class="text-center" style="font-weight: bold; color: black">MONTANT PAYÉ :
                                        <?php
                                        $ttHaP = 0;
                                        $Apayerp = 0;
                                        foreach($recherches as $rechingp){
                                            if($rechingp->statutid == 3){
                                                $ttHaP += $rechingp->valeur;
                                                $Apayerp += 2768 + (176*0.03*$ttHaP);
                                            }
                                        }
                                            //$Apayerp = 2768 + (176*0.03*$ttHaP);
                                            echo number_format($Apayerp, '2', ',', '.');
                                        ?>
                                    </td>
                            </tr>

                            </tbody>
                        </table>

                        <div class="text-right">

                            <a href="{{ route('exels_precarites', $code) }}">
                                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-primary">
                                    <i class="icon-folder" aria-hidden="true"></i>
                                </button>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection