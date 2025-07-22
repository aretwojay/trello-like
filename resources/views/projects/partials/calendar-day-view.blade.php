<!-- Vue journalière -->
@php
    $currentDate = \Carbon\Carbon::parse($date ?? now());
    $dayTasks = $tasks->filter(function($task) use ($currentDate) {
        return $task->due_date && $task->due_date->format('Y-m-d') === $currentDate->format('Y-m-d');
    });
@endphp

<div class="p-6">
    <div class="text-center mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $currentDate->format('l d F Y') }}
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            {{ $dayTasks->count() }} tâche{{ $dayTasks->count() !== 1 ? 's' : '' }} prévue{{ $dayTasks->count() !== 1 ? 's' : '' }}
        </p>
    </div>

    @if($dayTasks->isNotEmpty())
        <div class="space-y-4">
            @foreach($dayTasks->sortBy('created_at') as $task)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <!-- Checkbox -->
                    <form method="POST" action="{{ route('tasks.toggle-complete', [$project, $task]) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="flex-shrink-0">
                            @if($task->is_completed)
                                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full hover:border-gray-400"></div>
                            @endif
                        </button>
                    </form>

                    <!-- Contenu de la tâche -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('tasks.edit', [$project, $task]) }}" class="font-medium text-gray-900 hover:text-indigo-600 {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                {{ $task->title }}
                            </a>
                            
                            <!-- Badge de priorité -->
                            @if($task->priority && $task->priority !== 'medium')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $task->priority === 'high' ? '🔴 Haute' : '🔵 Basse' }}
                                </span>
                            @endif

                            <!-- Badge de colonne -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $task->column->name }}
                            </span>
                        </div>
                        
                        @if($task->description)
                            <p class="text-sm text-gray-600 mt-1 {{ $task->is_completed ? 'line-through' : '' }}">
                                {{ $task->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('tasks.edit', [$project, $task]) }}" class="text-gray-400 hover:text-indigo-600 p-1" title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche prévue</h3>
            <p class="mt-1 text-sm text-gray-500">Aucune tâche n'est planifiée pour cette date.</p>
        </div>
    @endif
</div>
