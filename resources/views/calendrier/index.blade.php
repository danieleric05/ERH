@extends('layouts.erh')

@section('content')

<div class="px-6 py-8">
    {{-- Section d'en-tête (Breadcrumb et Titre) --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-text-primary">
            Calendrier des Absences
        </h1>
        <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="icon-home"></i></a></li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-text-secondary">Gestion des autorisations</span>
                </li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-text-secondary">Calendrier</span>
                </li>
            </ol>
        </nav>
    </div>

<div class="mt-6 bg-white rounded-lg shadow-lg-soft p-6">
    <div id="calendrier"></div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('rhassets/vendor/fullcalendar/fullcalendar.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('rhassets/bundles/fullcalendarscripts.bundle.js') }}"></script>
<script src="{{ asset('rhassets/vendor/fullcalendar/fullcalendar.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#calendrier').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: '{{ route('calendrier.evenements') }}',
            eventColor: '#007bff'
        });
    });
</script>
@endpush
