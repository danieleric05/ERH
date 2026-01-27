@extends('layouts.erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des departements </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Configuration</li>
                    <li class="breadcrumb-item active">Liste des departements</li>
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

                <a onclick="addForm_recrutement()"  style="float: right; color: #fff;" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Ajouter un departement
                </a>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom" id="showAllDataHere">
                            <thead class="thead-dark">
                            <tr>
                                <th>Identifiant</th>
                                <th>Departement</th>
                                <th>Unité</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_departement as $listedata)
                                <tr>
                                    <td>{{ $listedata->id }}</td>
                                    <td>{{ $listedata->label }}</td>
                                    <td>{{ $label = \App\Unites::where('id', $listedata->uniteid)->first()->label }}</td>
                                    <td class="actions text-center">
                                        <a href="{{ url('edit/departements/data') }}" data-id="{{ $listedata->id }}" id="edit" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"  href="{{ url('delete/departements/data') }}"  data-id="{{ $listedata->id }}" id="deleteDeparte" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584">
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

    <div id="getalldata" data-url="{{ url('departements') }}"></div>

    @include('configuration.departement.modal_add')
    @include('configuration.departement.edit')


<script>
function tableSort() {
    return {
        sortBy: null,
        sortDir: 'asc',

        sort(column) {
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc';
            }
            this.sortTable();
        },

        sortTable() {
            const tbody = document.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const headers = document.querySelectorAll('thead th');
                let colIndex = 0;
                
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].textContent.toLowerCase().includes(this.sortBy.toLowerCase())) {
                        colIndex = i;
                        break;
                    }
                }

                const cellA = a.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                const cellB = b.querySelector('td:nth-child(' + (colIndex + 1) + ')');
                
                if (!cellA || !cellB) return 0;

                let valueA = cellA.textContent.trim();
                let valueB = cellB.textContent.trim();

                const dateA = new Date(valueA).getTime();
                const dateB = new Date(valueB).getTime();

                if (!isNaN(dateA) && !isNaN(dateB) && dateA > 0 && dateB > 0) {
                    return this.sortDir === 'asc' ? dateA - dateB : dateB - dateA;
                }

                return this.sortDir === 'asc'
                    ? String(valueA).localeCompare(String(valueB), 'fr-FR')
                    : String(valueB).localeCompare(String(valueA), 'fr-FR');
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    }
}
</script>
@endsection