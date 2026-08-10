@extends('erhselect')
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

            @include('success')
            @include('errors')

            <div class="card">

                <div class="body">
                    <form action="{{ url('add/equipes/addequipes') }}" method="POST">

                        <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Identifiant</label>
                                <input placeholder="Identifiant" name="id" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Equipe</label>
                                <input placeholder="Nom de l'équipe" name="label" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Liste des unités</label>
                                <select name="uniteid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($unites as $unite)
                                    <option value="{{ $unite->id }}">{{ $unite->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Chef d'equipe</label>
                                <select name="chefEquipeid" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    @foreach($Travailleur as $liste)
                                    <option value="{{ $liste->id }}">{{ $liste->nom }} - {{ $liste->prenom }} - {{ $liste->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Un mot sur l'equipe</label>
                                <textarea placeholder="Un mot sur l'equipe" class="form-control" name="description" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                        <div class="col-lg-12 col-sm-10 text-center" style="border: red 2px double; padding-top: 20px; padding-bottom:20px; padding-left: 15px; padding-right: 15px; margin-top: 40px">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>

                <div class="body">
                    <div class="table-responsive">

                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Identifiant</th>
                                <th>Equipes</th>
                                <th>Chef Equipe</th>
                                <th>Unite</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_equipe as $listedata)
                                <tr>
                                    <td>{{ $listedata->id }}</td>
                                    <td>{{ $listedata->label }}</td>
                                    <td>**********</td>
                                    @if($listedata->uniteid)
                                      <td>{{ $label = optional(\App\Unites::where('id', $listedata->uniteid)->first())->label }}</td>
                                    @endif
                                    @if(!$listedata->uniteid)
                                    <td></td>
                                    @endif
                                    <td class="actions text-center">
                                        <a href="{{ url('edit/equipes/data') }}" data-id="{{ $listedata->id }}" id="edit" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove" data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584">
                                            <i class="icon-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"  href="{{ url('delete/equipes/data') }}"  data-id="{{ $listedata->id }}" id="deleteEquipe" class="btn btn-sm btn-icon btn-pure btn-danger on-default button-remove" data-toggle="tooltip" data-original-title="Remove">
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