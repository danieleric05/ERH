@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Liste des journaliers en fin de contrat</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span>Recrutement</span>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Liste des journaliers en fin de contrat</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('liste_tous_travailleurs') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fa fa-arrow-left"></i> Retour à la liste des journaliers
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    @include('success')
    @include('errors')

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-900 text-white border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Matricule</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nom & Prénoms</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date d'embauche</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Date fin de contrat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Options</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data_travailleur as $listedata)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ $listedata->photo ? asset('rhassets/images/travailleurs/' . $listedata->photo) : asset('rhassets/images/travailleurs/default.png') }}"
                                 height="50" width="50"
                                 class="rounded-full object-cover w-12 h-12"
                                 alt="Photo {{ $listedata->nom }}">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('etapedeuxtravailleur', $listedata->id) }}"
                               title="MODIFIER"
                               class="text-red-600 font-bold hover:underline">
                                {{ $listedata->matricule }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-text-primary">
                            {{ $listedata->nom }} {{ $listedata->prenom }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary text-sm">
                            {{ $listedata->date_debut_contrat ? \Carbon\Carbon::parse($listedata->date_debut_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-bold text-sm">
                            {{ $listedata->date_fin_contrat ? \Carbon\Carbon::parse($listedata->date_fin_contrat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($listedata->etapeid == 3)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">CESSATION</span>
                            @elseif($listedata->etapeid == 4)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">CERTIFICAT DE TRAVAIL</span>
                            @elseif($listedata->etapeid == 5)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">DÉCLARATION CNPS</span>
                            @elseif($listedata->etapeid == 6)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">RECONDUIRE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if($listedata->etapeid != 3)
                                    <a href="javascript:void(0)"
                                       onclick="openDeclarationFinModal({{ $listedata->id }})"
                                       title="CESSATION/CERTIFICAT DE TRAVAIL/DÉCLARATION"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa fa-map-marker"></i>
                                    </a>
                                @endif

                                @if($listedata->etapeid == 3)
                                    <a href="javascript:void(0)"
                                       onclick="openReconduite({{ $listedata->id }})"
                                       title="RECONDUIRE LE TRAVAILLEUR"
                                       class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                @endif

                                <a href="{{ route('telechargerContratCessassion', ['id' => $listedata->id, 'download' => 'pdf']) }}"
                                   target="_blank"
                                   title="TÉLÉCHARGER LE CONTRAT"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-text-secondary">
                            Aucun journalier en fin de contrat dans les 20 prochains jours
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('excel_download_fin_contrat') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
            <i class="fa fa-file-excel-o"></i> Télécharger
        </a>
    </div>

    @include('travailleur.modal_reconduire')
    @include('travailleur.modal_declaration_fin')

@endsection
