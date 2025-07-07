@extends('erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des équipes </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Liste des équipes</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">
            <div class="card">

                <a onclick="addForm_equipe()"  style="float: right; color: #fff;" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Ajouter une équipe
                </a>

                <div class="body">
                   <h1>ERROR 277</h1>
                </div>
            </div>
        </div>

    </div>

    @include('configuration.equipe.modal_equipe')
    @include('configuration.equipe.edit')


@endsection