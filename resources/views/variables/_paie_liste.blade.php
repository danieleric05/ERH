{{-- Tableau des variables de paie d'un mois. Attend : $lignesPaie, $annee, $mois, $routeListe, $titre --}}
@php
    $moisNoms = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    $fmt = fn($v, $u) => $u === 'montant' ? number_format($v, 0, ',', ' ') . ' F' : rtrim(rtrim(number_format($v, 2, ',', ''), '0'), ',') . ($u === 'heure' ? ' h' : ' j');
@endphp
<div class="bg-white rounded-lg shadow-lg-soft overflow-hidden mb-8">
    <div class="px-6 py-4 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-text-primary">{{ $titre }} — {{ $moisNoms[$mois] }} {{ $annee }}</h2>
            <p class="text-xs text-slate-500">{{ count($lignesPaie) }} ligne(s). Données du fichier Excel importé et des saisies dans « Variables de paie ».</p>
        </div>
        <form method="GET" action="{{ route($routeListe) }}" class="flex items-end gap-2">
            <select name="mois" class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white">
                @foreach($moisNoms as $n => $lib)<option value="{{ $n }}" @selected($n == $mois)>{{ $lib }}</option>@endforeach
            </select>
            <input type="number" name="annee" value="{{ $annee }}" min="2019" max="2100" class="w-24 px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Afficher</button>
            <a href="{{ route('variables_paie', ['annee' => $annee, 'mois' => $mois]) }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium">Grille de paie</a>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-900 text-white border-b">
                    <th class="px-6 py-3 text-left text-sm font-semibold">Matricule</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Employé</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Variable</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold">Total</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Détail par semaine</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Origine</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lignesPaie as $l)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="px-6 py-2 text-sm font-semibold">{{ $l->matricule }}</td>
                        <td class="px-6 py-2 text-sm {{ $l->connu ? 'text-text-primary' : 'text-amber-700 italic' }}">{{ $l->nom }}</td>
                        <td class="px-6 py-2 text-sm text-text-secondary">{{ $l->libelle }}</td>
                        <td class="px-6 py-2 text-sm text-right font-semibold">{{ $fmt($l->total, $l->unite) }}</td>
                        <td class="px-6 py-2 text-xs text-slate-500">
                            @foreach($l->semaines as $sem => $val)<span class="inline-block mr-2">S{{ $sem }} : {{ rtrim(rtrim(number_format($val, 2, ',', ''), '0'), ',') }}</span>@endforeach
                        </td>
                        <td class="px-6 py-2 text-center">
                            @if($l->source === 'auto')
                                <span class="px-2 py-1 text-xs font-semibold text-sky-800 bg-sky-100 rounded-full" title="Calculé depuis Santé, Autorisations ou Sanctions">Calculé</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">Enregistré</span>
                            @endif
                        </td>
                        <td class="px-6 py-2 text-center">
                            <a href="{{ route('variables_paie_edit', ['matricule' => $l->matricule, 'annee' => $annee, 'mois' => $mois, 'code' => $l->code]) }}" title="Modifier" class="p-2 text-slate-700 hover:bg-slate-100 rounded-lg transition"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500"><p class="text-lg">Aucune donnée pour {{ $moisNoms[$mois] }} {{ $annee }}</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
