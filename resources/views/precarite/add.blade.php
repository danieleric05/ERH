@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="#" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des precarités </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Ajouter HA01</li>
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
                    <form action="{{ url('post_precarite_ho') }}" method="POST" role="form" class="form-auth-small">
                    @csrf
                        <div class="row clearfix">

                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select required="" name="type" class="form-control show-tick ms select2" data-placeholder="Select">
                                        <option>-DEROULER-</option>
                                        <option value="J">Journalier</option>
                                        <option value="E">Embauché</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="variables" class="control-label">Télécharger le fichier</label>
                                    <input required type="file" name="fichiers" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group" style="margin-top: 28px; padding-right: 60px;">
                                    <button style="padding-top: 9px; padding-bottom: 9px; padding-right: 33px; padding-left: 33px" type="submit" class="btn btn-danger">Enregistrer</button>
                                </div>
                            </div>

                        </div>

                    </form>     {!!Form::close() !!}

                    <div class="table-responsive">
                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-info">
                            <tr>
                                <th class="text-center">Matricule</th>
                                <th class="text-center">Element</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Variable</th>
                                <th class="text-center">Jours travaillés</th>
                                <th class="text-center">Valeur HA01</th>
                                <th class="text-center">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data_hao1 as $listedata)
                                <tr>
                                    <td class="text-center">{{ $listedata->matricule }}</td>
                                    <td class="text-center">{{ $listedata->element }}</td>
                                    <td class="text-center">{{ $listedata->code }}</td>
                                    <td class="text-center">{{ $listedata->variable }}</td>
                                    <td class="text-center"><?= $val = $listedata->valeur_ha01 - $listedata->variable ?></td>
                                    <td class="text-center">{{ $listedata->valeur_ha01 }}</td>
                                    <td class="text-center">{{ $listedata->dateha }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                            <div class="text-right">

                                <a href="{{ route('exels_precarites_debut') }}">
                                    <button title="EXPORTER EN EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-success">
                                        <i class="icon-bag" aria-hidden="true"></i> EXPORTER HAO1
                                    </button>
                                </a>

                                <br/>

                                <br/>

                                @if($verif_traitement_ha01 > 0)

                                    {!!Form::open (['url'=>['post_precarite_finalite'], 'method'=>'post', 'role'=>'form' ])!!}
                                    <button title="FINALISER HA01" style="padding-left: 20px; padding-right: 20px" type="submit" class="btn btn-danger">
                                        <i class="icon-calculator" aria-hidden="true"></i> FINALISER &nbsp;
                                    </button>
                                    {!!Form::close() !!}

                                @endif


                            </div>


                    </div>

                </div>





                <div class="body" style="display: none">
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-12">
                            <p><b>Basic Example</b></p>
                            <div id="nouislider_basic_example"></div>
                            <div class="m-t-20 font-12"><b>Value: </b><span class="js-nouislider-value"></span></div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <p><b>Range Example</b></p>
                            <div id="nouislider_range_example"></div>
                            <div class="m-t-20 font-12"><b>Value: </b><span class="js-nouislider-value"></span></div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

    </div>

@endsection