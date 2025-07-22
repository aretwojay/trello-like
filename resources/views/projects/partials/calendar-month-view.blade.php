<!-- Vue mensuelle -->
@php
    $currentDate = \Carbon\Carbon::parse($date ?? now());
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();
    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
    $weeks = [];
    
    $current = $startOfCalendar->copy();
    while ($current <= $endOfCalendar) {
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $current->copy();
            $current->addDay();
        }
        $weeks[] = $week;
    }
@endphp

<!-- En-têtes des jours -->
<div class="grid grid-cols-7 divide-x divide-gray-200 border-b calendar-header-gradient">
    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $index => $day)
        <div class="px-4 py-4 text-center transition-all duration-200 hover:bg-white/50">
            <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                {{ substr($day, 0, 3) }}
            </div>
            <div class="text-sm font-medium text-gray-800 mt-1 {{ in_array($index, [5, 6]) ? 'text-blue-600' : '' }}">
                {{ $day }}
            </div>
        </div>
    @endforeach
</div>

<!-- Grille du calendrier -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
@foreach($weeks as $week)
    <div class="grid grid-cols-7 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
        @foreach($week as $day)
            @php
                $dayTasks = $tasks->filter(function($task) use ($day) {
                    return $task->due_date && $task->due_date->format('Y-m-d') === $day->format('Y-m-d');
                });
                $isCurrentMonth = $day->month === $currentDate->month;
                $isToday = $day->isToday();
                $isPast = $day->isPast() && !$isToday;
                $isWeekend = in_array($day->dayOfWeek, [6, 0]); // Samedi = 6, Dimanche = 0
            @endphp
            <div class="min-h-[140px] p-3 calendar-day calendar-day-hover relative group border-r border-b
                {{ $isCurrentMonth ? 'bg-white hover:bg-gray-50' : 'bg-gray-50/50' }}
                {{ $isToday ? 'bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200' : '' }}
                {{ $isWeekend && $isCurrentMonth ? 'weekend-pattern' : '' }}
                {{ $isPast ? 'opacity-75' : '' }}">
                
                <!-- Numéro du jour avec indicateurs visuels -->
                <div class="flex items-center justify-between mb-3">
                    <div class="relative">
                        <span class="text-sm font-semibold transition-all duration-200
                            {{ $isCurrentMonth ? ($isToday ? 'text-blue-600' : 'text-gray-900') : 'text-gray-400' }}
                            {{ $isWeekend && $isCurrentMonth ? 'text-blue-600' : '' }}">
                            {{ $day->format('d') }}
                        </span>
                        @if($isToday)
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full priority-indicator shadow-lg"></div>
                        @endif
                    </div>
                    
                    <!-- Indicateur du nombre de tâches -->
                    @if($dayTasks->count() > 0)
                        <div class="flex items-center space-x-1">
                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-gradient-to-r from-gray-600 to-gray-700 rounded-full shadow-sm">
                                {{ $dayTasks->count() }}
                            </span>
                            @if($dayTasks->where('is_completed', false)->count() > 0)
                                <div class="w-2 h-2 bg-gradient-to-r from-orange-400 to-red-400 rounded-full priority-indicator"></div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Tâches du jour avec design amélioré -->
                <div class="space-y-2">
                    @foreach($dayTasks->take(3) as $task)
                        <div class="group/task relative calendar-task-enter">
                            <div class="relative calendar-task calendar-task-shine text-xs px-3 py-2 rounded-lg shadow-sm border cursor-pointer 
                                transition-all duration-300 hover:shadow-lg hover:scale-[1.02] overflow-hidden
                                {{ $task->is_completed ? 'bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 border-emerald-200 opacity-75' : '' }}
                                {{ $task->priority === 'high' && !$task->is_completed ? 'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border-red-200' : '' }}
                                {{ $task->priority === 'medium' && !$task->is_completed ? 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 border-amber-200' : '' }}
                                {{ $task->priority === 'low' && !$task->is_completed ? 'bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 border-blue-200' : '' }}
                                {{ !$task->priority && !$task->is_completed ? 'bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border-gray-200' : '' }}"
                                title="{{ $task->title }} - {{ $task->description }}"
                                onclick="window.location.href='{{ route('tasks.edit', [$project, $task]) }}'">
                                
                                <!-- Indicateur de priorité -->
                                <div class="flex items-center space-x-2">
                                    @if($task->priority === 'high')
                                        <div class="w-2 h-2 bg-gradient-to-r from-red-500 to-pink-500 rounded-full priority-indicator"></div>
                                    @elseif($task->priority === 'medium')
                                        <div class="w-2 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full priority-indicator"></div>
                                    @elseif($task->priority === 'low')
                                        <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full priority-indicator"></div>
                                    @endif
                                    
                                    <span class="flex-1 font-medium {{ $task->is_completed ? 'line-through' : '' }}">
                                        {{ Str::limit($task->title, 18) }}
                                    </span>
                                    
                                    @if($task->is_completed)
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Tooltip au survol amélioré -->
                            <div class="absolute z-20 invisible group-hover/task:visible calendar-tooltip bg-gray-900/90 text-white text-xs rounded-xl px-4 py-3 -top-16 left-0 min-w-max max-w-xs opacity-0 group-hover/task:opacity-100 transition-all duration-300">
                                <div class="font-semibold">{{ $task->title }}</div>
                                @if($task->description)
                                    <div class="text-gray-300 mt-1">{{ Str::limit($task->description, 50) }}</div>
                                @endif
                                <div class="text-gray-400 text-xs mt-1">
                                    Cliquez pour modifier
                                </div>
                                <div class="absolute top-full left-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900/90"></div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($dayTasks->count() > 3)
                        <div class="text-xs text-gray-600 px-3 py-2 bg-gradient-to-r from-gray-100 to-slate-100 rounded-lg border border-dashed border-gray-300 calendar-task-enter">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ $dayTasks->count() - 3 }} tâches supplémentaires</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Message pour les jours vides -->
                    @if($dayTasks->count() === 0 && $isCurrentMonth)
                        <div class="opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="text-xs text-gray-400 px-3 py-3 border-2 border-dashed border-gray-200 rounded-lg text-center hover:border-blue-300 hover:text-blue-500 cursor-pointer transition-all duration-200"
                                onclick="// Ajouter une nouvelle tâche pour cette date">
                                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <div class="font-medium">Ajouter une tâche</div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Badge pour les weekends -->
                @if($isWeekend && $isCurrentMonth)
                    <div class="absolute top-2 right-2 opacity-20 group-hover:opacity-60 transition-all duration-200">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endforeach
</div>
