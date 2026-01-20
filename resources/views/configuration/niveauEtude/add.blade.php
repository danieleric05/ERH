@extends('layouts.erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des pays </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Liste des niveaux d'etudes</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">
            <div class="card">

                <a onclick="addForm_niveauEtude ()"  style="float: right; color: #fff;" data-toggle="modal" data-target="#largeModal" style="float: right" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Ajouter un niveau d'etude
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Niveau</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_niveauEtude as $listedata)
                                <tr>
                                    <td>{{ $listedata->label }}</td>
                                    <td class="actions text-center">
                                        <a href="{{ url('edit/niveauEtude/data') }}" data-id="{{ $listedata->id }}" id="edit" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"  href="{{ url('delete/niveauEtude/data') }}"  data-id="{{ $listedata->id }}" id="deletePays" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" data-toggle="tooltip" data-original-title="Remove">
                                            <i class="icon-trash" aria-hidden="true"></i>
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

    @include('configuration.niveauEtude.modal_niveauEtude')
    @include('configuration.niveauEtude.edit')

@endsection