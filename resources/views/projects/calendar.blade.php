@extends('layout.default')

@section('title', $project->name . ' - Vue Calendrier')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('projects.partials.calendar-header')

    <!-- Contenu principal - Vue Calendrier -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('projects.partials.calendar-navigation')

        <!-- Calendrier -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($viewType === 'month')
                @include('projects.partials.calendar-month-view')
            @elseif($viewType === 'week')
                @include('projects.partials.calendar-week-view')
            @else
                @include('projects.partials.calendar-day-view')
            @endif
        </div>

        @include('projects.partials.calendar-legend')
    </div>

    @include('projects.partials.calendar-task-modal')
</div>
@endsection
