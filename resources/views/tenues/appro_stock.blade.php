@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des tenues </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Tenues</li>
                    <li class="breadcrumb-item active">Approvisionnement du stock</li>
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

                <form action="{{ url('post_appro_stock') }}" method="POST" role="form">
    @csrf

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label>Date</label>
                            <div class="input-group mb-3">
                                <input name="date_reception" style="height: 40px; color: black; font-weight: bold" value="{{ date('Y-m-d') }}" type="date" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tenuerecu" class="control-label">Article</label>
                                <select name="tenuerecu" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($data_ArticleRecu as $data)
                                         <option value="{{ $data->id }}">{{ $data->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantite" class="control-label">Quantité</label>
                                <input name="quantite" class="form-control" id="quantite" type="number">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="fournisseur" class="control-label">Fournisseur</label>
                                <input name="fournisseur" class="form-control" id="fournisseur" type="text">
                            </div>
							
                        </div>

                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('listetenues') }}">
                                <button style="padding-left: 35px; padding-right: 35px" type="button" class="btn btn-danger">
                                    Liste
                                </button>
                            </a>
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

                </form>

            </div>
        </div>
		
		
		<div class="col-lg-12">

            <div class="card">
                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Fournisseur</th>
                                <th class="text-center">Date de reception</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
							 @foreach($data_ApproTenues as $tenues)
                                <tr>

                                    <td>{{ $id_article = optional(App\ArticleRecu::where('id', $tenues->articleid )->first())->label }}</td>
                                    <td>{{ $tenues->quantite }}</td>
                                    <td>{{ $tenues->fournisseur }}</td>
									<td class="text-center">{{ $tenues->date_recep }}</td>
                                    <td class="text-center">
										<a title="Modifier" href="#" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip">
                                            <i class="icon-pencil" aria-hidden="true"></i>
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