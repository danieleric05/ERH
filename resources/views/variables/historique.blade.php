@extends('layouts.erh')
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
                    <li class="breadcrumb-item">Recrutement</li>
                    <li class="breadcrumb-item active">Historiques des variables</li>
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

                    <form action="{{ url('post_search_varaiables') }}" method="POST" role="form" class="form-auth-small">
                    @csrf
                        <div class="row clearfix">

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="variablesid" class="control-label">Variables</label>
                                <select required name="variablesid" class="form-control select2-active">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">DIMANCHE</option>
                                    <option value="2">FERIE</option>
                                    <option value="3">JOUR OUVRABLE</option>
                                    <option value="4">TOUS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cas_variables" class="control-label">Cas</label>
                                <select required name="cas_variables" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">-DEROULER-</option>
                                    <option value="1">RETARD D'ENROLEMENT</option>
                                    <option value="2">DEFAUT DE POINTAGE</option>
                                    <option value="3">OUBLI DE POINTAGE</option>
                                    <option value="4">DEFAUT D'EMPREINTE</option>
                                    <option value="5">TOUS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Début</label>
                                <input required type="date"  value="<?= date('Y-m-d')?>" name="beginn" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Fin</label>
                                <input required type="date" value="<?= date('Y-m-d')?>" max="<?= date('Y-m-d') ?>" name="endd" class="form-control text-uppercase" maxlength="50">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-12" style="margin-top: 28px">
                            <button type="submit" class="btn btn-danger" style="padding-left: 25px; font-weight: bold; font-size: 14px; padding-right: 25px; padding-top: 7px; padding-bottom: 6px">
                                Rechercher
                            </button>
                        </div>

                    </div>
                    </form>

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
                                <th>Matricule</th>
                                <th>Jour</th>
                                <th>Variables</th>
                                <th class="text-center cursor-pointer hover:bg-slate-800 transition select-none" @click="sort('nombredejour')">
                            <div class="flex items-center gap-2">
                                Nombre de jour
                                <span x-show="sortBy === 'nombredejour'" :class="sortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'" class="fa text-xs"></span>
                            </div>
                        </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!($recherches))
                                    AUCUNE DONNÉE DISPONIBLE
                            @endif
                            @if($recherches)
                                @foreach($recherches as $rech)

                                    <?php
                                    $debut = strtotime($rech->debut);
                                    $fin = strtotime($rech->fin);
                                    $dif = ceil(abs($fin - $debut) / 86400) + 1;
                                    $nb_jour = intval($dif);
                                    ?>

                                    <tr>
                                        <td>
                                            @foreach( unserialize($rech->travailleurid) as $servaiable )
                                                <span title="{{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->nom }} {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->prenom }}" class="badge badge-dark" style="font-weight: bold">
                                                    {{ $trava = \App\Travailleur::where('matricule', $servaiable )->first()->matricule }}
                                                </span> <br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($rech->type_variable == 1)
                                                DIMANCHE
                                            @endif
                                            @if($rech->type_variable == 2)
                                                FERIE
                                            @endif
                                            @if($rech->type_variable == 3)
                                                    JOUR OUVRABLE
                                            @endif
                                        </td>
                                        <td>
                                            @if($rech->cas_variables == 1)
                                                RETARD D'ENROLEMENT
                                            @endif
                                            @if($rech->cas_variables == 2)
                                                DEFAUT DE POINTAGE
                                            @endif
                                            @if($rech->cas_variables == 3)
                                                OUBLI DE POINTAGE
                                            @endif
                                            @if($rech->cas_variables == 4)
                                                DEFAUT D'EMPREINTE
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $nb_jour }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>

                        <div class="text-right">

                            <a href="{{ route('excel_download_variables', $code) }}">
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