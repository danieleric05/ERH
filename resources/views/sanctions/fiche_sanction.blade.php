@extends('erhform')
@section('content')

            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Modules </h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Sanction</li>
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
                            <h2>Telecharger</h2>
                        </div>
                        <div class="body text-center perso_color">
                            <a href="{{ route('listesanctions') }}">
                                <button type="button" class="btn btn-outline-danger">Retour à la liste</button>
                            </a>
							
                        </div>
                        <div class="body text-center perso_color">

							<?php
								$sanctions = [];
								if (!empty($sanct->employeid)) {
									try {
										$sanctions = unserialize($sanct->employeid);
										if (!is_array($sanctions)) {
											$sanctions = [$sanctions];
										}
									} catch (Exception $e) {
										$sanctions = [];
									}
								}
							?>
							@forelse($sanctions as $sant)
								<a target="_blank" title="{{ optional(App\Travailleur::where('matricule', $sant)->first())->nom }} {{ optional(App\Travailleur::where('matricule', $sant)->first())->prenom }}" href="{{ route('telechargerSanctions', ['mat' => $sant, 'idsanc' => $sanct->id]) }}?download=pdf">
										<button type="button" class="btn btn-outline-secondary">DOWNLOAD - {{ $sant}} </button>
								</a>
							@empty
								<p class="text-slate-500">Aucune sanction à télécharger</p>
							@endforelse
                        </div>
                    </div>
                </div>

            </div>

@endsection