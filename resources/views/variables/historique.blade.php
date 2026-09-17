@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Historiques des variables</h1>
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                <i class="fa fa-home"></i> Accueil
            </a>
            <span class="text-text-secondary">/</span>
            <span>Recrutement</span>
            <span class="text-text-secondary">/</span>
            <span class="text-text-primary font-semibold">Historiques des variables</span>
        </nav>
    </div>

    <!-- Formulaire de recherche -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8 mb-8">
        <form action="{{ url('post_search_varaiables') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Variables</label>
                    <select required name="variablesid" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">DIMANCHE</option>
                        <option value="2">FERIE</option>
                        <option value="3">JOUR OUVRABLE</option>
                        <option value="4">TOUS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Cas</label>
                    <select required name="cas_variables" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        <option value="1">RETARD D'ENROLEMENT</option>
                        <option value="2">DEFAUT DE POINTAGE</option>
                        <option value="3">OUBLI DE POINTAGE</option>
                        <option value="4">DEFAUT D'EMPREINTE</option>
                        <option value="5">TOUS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Début</label>
                    <input required type="date" value="{{ date('Y-m-d') }}" name="beginn"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-primary mb-2">Fin</label>
                    <input required type="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" name="endd"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent">
                </div>
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Résultats -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jour</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Variables</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Nombre de jour</th>
                    </tr>
                </thead>
                <tbody>
                @if($recherches)
                    @foreach($recherches as $rech)
                        @php
                            $debut = strtotime($rech->debut);
                            $fin = strtotime($rech->fin);
                            $nb_jour = intval(ceil(abs($fin - $debut) / 86400) + 1);
                        @endphp
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                @foreach(unserialize($rech->travailleurid) as $servaiable)
                                    @php $tv = $travailleursByMatricule->get($servaiable); @endphp
                                    <span title="{{ optional($tv)->nom }} {{ optional($tv)->prenom }}"
                                          class="inline-block px-2 py-1 mb-1 text-xs font-bold rounded-full bg-slate-800 text-white">
                                        {{ optional($tv)->matricule }}
                                    </span><br>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-text-primary">
                                @if($rech->type_variable == 1) DIMANCHE @endif
                                @if($rech->type_variable == 2) FERIE @endif
                                @if($rech->type_variable == 3) JOUR OUVRABLE @endif
                            </td>
                            <td class="px-6 py-4 text-text-primary">
                                @if($rech->cas_variables == 1) RETARD D'ENROLEMENT @endif
                                @if($rech->cas_variables == 2) DEFAUT DE POINTAGE @endif
                                @if($rech->cas_variables == 3) OUBLI DE POINTAGE @endif
                                @if($rech->cas_variables == 4) DEFAUT D'EMPREINTE @endif
                            </td>
                            <td class="px-6 py-4 text-center text-text-primary">{{ $nb_jour }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-text-secondary">Aucune donnée disponible</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 flex justify-end">
            <a href="{{ route('excel_download_variables', $code) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-accent text-white font-semibold rounded-lg hover:bg-plastica-blue transition">
                <i class="icon-folder"></i> EXCEL
            </a>
        </div>
    </div>

@endsection
