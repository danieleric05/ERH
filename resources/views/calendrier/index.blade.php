@extends('erh')

@section('content')

<div class="block-header">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Calendrier des Absences</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('bienvenue') }}"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item">Gestion des autorisations</li>
                <li class="breadcrumb-item active">Calendrier</li>
            </ul>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div id="calendrier"></div>
            </div>
        </div>
    </div>
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
