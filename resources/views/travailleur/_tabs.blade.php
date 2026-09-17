@php
    $travailleurTabs = [
        ['route' => 'liste_travailleurs', 'label' => 'Tous les travailleurs'],
        ['route' => 'liste_embauches', 'label' => 'Embauchés'],
        ['route' => 'liste_tous_travailleurs', 'label' => 'Journaliers'],
        ['route' => 'liste_cessations', 'label' => 'Cessations'],
        ['route' => 'liste_declarations', 'label' => 'Déclarations CNPS'],
        ['route' => 'liste_certificat_travail', 'label' => 'Certificats de travail'],
    ];
@endphp

<div class="mb-6 border-b border-slate-200">
    <nav class="flex flex-wrap gap-1 -mb-px">
        @foreach($travailleurTabs as $tab)
            <a href="{{ route($tab['route']) }}"
               class="px-4 py-2.5 text-sm font-semibold border-b-2 transition
                      {{ request()->routeIs($tab['route'])
                            ? 'border-primary-accent text-primary-accent'
                            : 'border-transparent text-text-secondary hover:text-text-primary hover:border-slate-300' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>
