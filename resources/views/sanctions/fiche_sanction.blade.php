@extends('layouts.erh')
@section('content')

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-text-primary mb-4">Fiche de sanction</h1>
                <nav class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="{{ url('bienvenue') }}" class="hover:text-text-primary">
                        <i class="fa fa-home"></i> Accueil
                    </a>
                    <span class="text-text-secondary">/</span>
                    <span class="text-text-primary font-semibold">Sanction</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('listesanctions') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition">
                    <i class="fa fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    @include('success')
    @include('errors')

    <div class="bg-white rounded-lg shadow-lg-soft p-8 text-center">
        @php
            $sanctions = [];
            if (!empty($sanct->employeid)) {
                try {
                    $sanctions = unserialize($sanct->employeid);
                    if (!is_array($sanctions)) {
                        $sanctions = [$sanctions];
                    }
                } catch (Exception $e) {
                    $sanctions = [];
                }
            }
        @endphp
        <div class="flex flex-wrap justify-center gap-3">
            @forelse($sanctions as $sant)
                <a target="_blank"
                   title="{{ optional(App\Travailleur::where('matricule', $sant)->first())->nom }} {{ optional(App\Travailleur::where('matricule', $sant)->first())->prenom }}"
                   href="{{ route('telechargerSanctions', ['mat' => $sant, 'idsanct' => $sanct->id]) }}?download=pdf"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 rounded-lg text-text-primary hover:bg-slate-50 transition">
                    <i class="fa fa-file-pdf-o"></i> TÉLÉCHARGER - {{ $sant }}
                </a>
            @empty
                <p class="text-text-secondary">Aucune sanction à télécharger</p>
            @endforelse
        </div>
    </div>

@endsection
