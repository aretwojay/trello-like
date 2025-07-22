<!-- Vue hebdomadaire -->
@php
    $currentDate = \Carbon\Carbon::parse($date ?? now());
    $startOfWeek = $currentDate->copy()->startOfWeek();
    $weekDays = [];
    for ($i = 0; $i < 7; $i++) {
        $weekDays[] = $startOfWeek->copy()->addDays($i);
    }
@endphp

<!-- En-têtes des jours de la semaine -->
<div class="grid grid-cols-8 divide-x divide-gray-200 border-b">
    <div class="px-4 py-3 text-center text-sm font-medium text-gray-500 bg-gray-50">
        Horaire
    </div>
    @foreach($weekDays as $day)
        <div class="px-4 py-3 text-center text-sm font-medium text-gray-500 bg-gray-50">
            <div class="font-semibold">{{ $day->format('D') }}</div>
            <div class="{{ $day->isToday() ? 'font-bold text-blue-600' : '' }}">{{ $day->format('d/m') }}</div>
        </div>
    @endforeach
</div>

<!-- Grille horaire (simplifiée) -->
@for($hour = 8; $hour <= 18; $hour++)
    <div class="grid grid-cols-8 divide-x divide-gray-200 border-b min-h-[60px]">
        <div class="px-4 py-2 text-sm text-gray-500 bg-gray-50 flex items-center justify-center">
            {{ sprintf('%02d:00', $hour) }}
        </div>
        @foreach($weekDays as $day)
            @php
                $dayTasks = $tasks->filter(function($task) use ($day) {
                    return $task->due_date && $task->due_date->format('Y-m-d') === $day->format('Y-m-d');
                });
            @endphp
            <div class="p-2">
                @if($hour === 9) <!-- Afficher les tâches seulement à 9h pour éviter la répétition -->
                    @foreach($dayTasks as $task)
                        <div class="text-xs px-2 py-1 mb-1 rounded truncate cursor-pointer hover:opacity-80
                            {{ $task->is_completed ? 'bg-green-100 text-green-800 line-through' : '' }}
                            {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $task->priority === 'medium' && !$task->is_completed ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $task->priority === 'low' && !$task->is_completed ? 'bg-blue-100 text-blue-800' : '' }}"
                            title="{{ $task->title }}"
                            onclick="window.location.href='{{ route('tasks.edit', [$project, $task]) }}'">
                            {{ Str::limit($task->title, 15) }}
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
@endfor
