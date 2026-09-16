@extends('layouts.erh')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2>
                    <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Liste des cessations
                </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Liste des journaliers en fin de contrat</li>
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

                <a href="{{ route('liste_tous_travailleurs') }}" style="float: right" class="btn btn-danger m-b-15 m-t-10 m-r-20">
                    <i class="icon wb-plus" aria-hidden="true"></i> Retour à la liste des journaliers
                </a>

                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover js-basic-example dataTable table-custom">
                            <thead class="thead-dark">
                            <tr>
                                <th>Image</th>
                                <th>Matricule</th>
                                <th>Nom & Prénoms</th>
                                <th>Date d'embauche</th>
                                <th>Date fin de contrat</th>
                                <th class="text-center cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('etat')">
                            <div class="flex items-center gap-2">
                                Etat
                                <span x-show="sortBy === 'etat'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data_travailleur as $listedata)
                                <tr>
                                    <td>
                                        <img src="{{ $listedata->photo
                                                ? asset('rhassets/images/travailleurs/' . $listedata->photo)
                                                : asset('rhassets/images/travailleurs/default.png') }}"
                                             alt="Photo {{ $listedata->nom }}"
                                             height="50" width="50"
                                             class="rounded-circle user-photo">
                                    </td>
                                    <td style="color: black; font-weight: bold">
                                        <a title="MODIFIER" style="color: red" href="{{ route('etapedeuxtravailleur', $listedata->id) }}">
                                        {{ $listedata->matricule }}
                                        </a>
                                    </td>
                                    <td title="#">{{ $listedata->nom.' '.$listedata->prenom }}</td>

                                    <td>{{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}</td>
                                    <td style="color: red; font-weight: bold">{{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}</td>
                                    <td class="text-center" style="color: red; font-weight: bold">
                                        @if($listedata->etapeid == 3)
                                            <span class="badge badge-success">CESSASSION</span>
                                        @endif
                                        @if($listedata->etapeid == 4)
                                            <span class="badge badge-danger">CERTIFICAT DE TRAVAIL</span>
                                        @endif
                                        @if($listedata->etapeid == 5)
                                            <span class="badge badge-default">DECLARATION CNPS</span>
                                        @endif
                                        @if($listedata->etapeid == 6)
                                            <span class="badge badge-primary">RECONDUIRE</span>
                                        @endif
                                    </td>
                                    <td class="actions text-center">

                                        @if($listedata->etapeid != 3)
                                        <a title="CESSASSION/CERTIFICAT DE TRAVAIL/DECLARATION"  data-toggle="tooltip" data-original-title="Remove" aria-describedby="tooltip270584" id="declaration_new" data-id="{{ $listedata->id }}" href="{{ url('action/declaration/travailleurs') }}" class="btn btn-sm btn-icon btn-pure btn-primary on-default button-remove">
                                            <i class="icon-map" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if($listedata->etapeid == 3)
                                            <a href="javascript:void(0)" title="RECONDUIRE LE TRAVAILLEUR" onclick="openReconduite({{ $listedata->id }})" class="btn btn-sm btn-icon btn-pure btn-dark on-default button-remove">
                                                <i class="icon-envelope" aria-hidden="true"></i>
                                            </a>
                                        @endif

                                        <a title="TELECHARGER LE CONTRAT" target="_blank" href="{{ route('telechargerContratCessassion',['id'=>$listedata->id, 'download'=>'pdf']) }}" class="btn btn-sm btn-danger btn-icon btn-pure on-default button-remove">
                                            <i class="icon-doc" aria-hidden="true"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <a href="{{ route('excel_download_fin_contrat') }}">
                <button title="EXCEL" style="padding-left: 20px; padding-right: 20px" type="button" class="btn btn-success">
                    <i class="icon-folder" aria-hidden="true"></i> <b>TELECHARGER</b>
                </button>
            </a>

        </div>

    </div>

    @include('travailleur.modal_reconduire')
    @include('travailleur.modal_declaration_fin')


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
            const rows = Array.from(tbody.querySelectorAll('tr:not(:last-child)'));

            rows.sort((a, b) => {
                let valueA, valueB;

                // Récupérer les données de la colonne
                const cells = Array.from(a.querySelectorAll('td'));
                if (cells.length === 0) return 0;

                // Déterminer l'index de la colonne
                let colIndex = 0;
                const headers = document.querySelectorAll('thead th');
                let clickCount = 0;
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].textContent.toLowerCase().includes(this.sortBy.toLowerCase())) {
                        colIndex = i;
                        break;
                    }
                }

                valueA = a.querySelector('td:nth-child(' + (colIndex + 1) + ')')?.textContent.trim() || '';
                valueB = b.querySelector('td:nth-child(' + (colIndex + 1) + ')')?.textContent.trim() || '';

                // Essayer de convertir en date
                const dateA = new Date(valueA).getTime();
                const dateB = new Date(valueB).getTime();

                if (!isNaN(dateA) && !isNaN(dateB) && dateA > 0 && dateB > 0) {
                    return this.sortDir === 'asc' ? dateA - dateB : dateB - dateA;
                }

                // Comparaison textuelle
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