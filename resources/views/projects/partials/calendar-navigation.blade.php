<!-- Navigation et options du calendrier -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <!-- Navigation de dates -->
        <div class="flex items-center space-x-4">
            @php
                $currentDate = \Carbon\Carbon::parse($date ?? now());
                $prevDate = $viewType === 'month' ? $currentDate->copy()->subMonth() : $currentDate->copy()->subWeek();
                $nextDate = $viewType === 'month' ? $currentDate->copy()->addMonth() : $currentDate->copy()->addWeek();
            @endphp
            
            <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view={{ $viewType }}&date={{ $prevDate->format('Y-m-d') }}" 
               class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-900">
                    @if($viewType === 'month')
                        {{ $currentDate->format('F Y') }}
                    @elseif($viewType === 'week')
                        Semaine du {{ $currentDate->startOfWeek()->format('d M') }} au {{ $currentDate->endOfWeek()->format('d M Y') }}
                    @else
                        {{ $currentDate->format('d F Y') }}
                    @endif
                </h2>
            </div>

            <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view={{ $viewType }}&date={{ $nextDate->format('Y-m-d') }}" 
               class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <!-- Bouton Aujourd'hui -->
            <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view={{ $viewType }}&date={{ now()->format('Y-m-d') }}" 
               class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Aujourd'hui
            </a>
        </div>

        <!-- Options de vue -->
        <div class="flex items-center space-x-2">
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view=month&date={{ $currentDate->format('Y-m-d') }}" 
                   class="px-3 py-1 text-sm font-medium rounded-md {{ $viewType === 'month' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Mois
                </a>
                <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view=week&date={{ $currentDate->format('Y-m-d') }}" 
                   class="px-3 py-1 text-sm font-medium rounded-md {{ $viewType === 'week' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Semaine
                </a>
                <a href="{{ route('projects.show', $project) }}?view=calendar&calendar_view=day&date={{ $currentDate->format('Y-m-d') }}" 
                   class="px-3 py-1 text-sm font-medium rounded-md {{ $viewType === 'day' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Jour
                </a>
            </div>
        </div>
    </div>
</div>
