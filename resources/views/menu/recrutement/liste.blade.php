@extends('erh')
@section('content')

    <section class="admin-content" data-select2-id="12">
        <div class="bg-dark">
            <div class="container  m-b-30">
                <div class="row">
                    <div class="col-12 text-white p-t-40 p-b-90">
                    </div>
                </div>
            </div>
        </div>

        <div class="container  pull-up" data-select2-id="11">
            <div class="row" data-select2-id="10">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-b-0"> Liste des travailleurs </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-t-10">
                                <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example" class="table dataTable" style="width: 100%;" role="grid" aria-describedby="example_info">
                                                <thead>
                                                <tr>
                                                    <th>Matricule </th>
                                                    <th>Nom </th>
                                                    <th>Prénoms </th>
                                                    <th>Contacts </th>
                                                    <th>Date d'embauche </th>
                                                    <th title="Date de fin de contrat">Fin de contrat </th>
                                                    <th>Departement </th>
                                                    <th>Equipe </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">Airi Satou</td>
                                                        <td>Accountant</td>
                                                        <td>Tokyo</td>
                                                        <td>33</td>
                                                        <td>2008/11/28</td>
                                                        <td>$162,700</td>
                                                        <td>$162,700</td>
                                                        <td>$162,700</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2  btn-rounded-circle btn-dark">
                                                                <i class="mdi mdi-folder-edit"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2  btn-rounded-circle btn-danger">
                                                                <i class="mdi mdi-trash-can"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection