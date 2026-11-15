@props(['counts', 'title' => 'Task progress'])

@php
    $segments = [
        'todo' => ['label' => 'To Do', 'bar' => 'bg-slate-400'],
        'in_progress' => ['label' => 'In Progress', 'bar' => 'bg-blue-500'],
        'review' => ['label' => 'In Review', 'bar' => 'bg-amber-500'],
        'done' => ['label' => 'Done', 'bar' => 'bg-emerald-500'],
    ];

    $total = collect($segments)->keys()->sum(fn ($key) => (int) $counts->get($key, 0));
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <div class="flex items-baseline justify-between">
        <h2 class="text-lg font-bold">{{ $title }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $total }} {{ $total === 1 ? 'task' : 'tasks' }}</p>
    </div>

    @if ($total === 0)
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            No tasks yet. The progress bar will appear once tasks are created.
        </p>
    @else
        <div class="mt-4 flex h-4 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
            @foreach ($segments as $key => $segment)
                @php $n = (int) $counts->get($key, 0); @endphp
                @if ($n > 0)
                    <div class="{{ $segment['bar'] }}"
                         style="width: {{ round($n / $total * 100, 2) }}%"
                         title="{{ $segment['label'] }}: {{ $n }}"></div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="mt-4 grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
        @foreach ($segments as $key => $segment)
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full {{ $segment['bar'] }}"></span>
                <span class="text-gray-600 dark:text-gray-300">{{ $segment['label'] }}</span>
                <span class="font-bold">{{ (int) $counts->get($key, 0) }}</span>
            </div>
        @endforeach
    </div>
</div>