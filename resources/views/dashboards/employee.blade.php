@php
    $priorityStyles = [
        'high' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
        'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
        'low' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300',
    ];
@endphp

<x-layouts.app title="Employee Dashboard">
    <div class="space-y-6">
        <x-welcome-banner :subtitle="$subtitle" />

        @if ($flag && $flag->is_at_risk)
            <div class="flex items-start gap-4 rounded-2xl bg-rose-50 p-5 ring-1 ring-rose-200 dark:bg-rose-500/10 dark:ring-rose-500/30">
                <span class="text-3xl">🔥</span>
                <div>
                    <p class="text-lg font-bold text-rose-800 dark:text-rose-200">Take a Break!</p>
                    <p class="text-sm text-rose-700 dark:text-rose-300">You have too many tight deadlines.</p>
                </div>
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="To Do" :value="(int) $statusCounts->get('todo', 0)" icon="📝" color="slate" />
            <x-stat-card label="In Progress" :value="(int) $statusCounts->get('in_progress', 0)" icon="🚧" color="blue" />
            <x-stat-card label="In Review" :value="(int) $statusCounts->get('review', 0)" icon="🔍" color="amber" />
            <x-stat-card label="Overdue" :value="$overdue" icon="⏰" color="rose" hint="Past the deadline and not approved yet" />
        </div>

        <x-status-bar :counts="$statusCounts" title="My progress" />

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <h2 class="text-lg font-bold">Upcoming deadlines</h2>

            @if ($upcoming->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    No open tasks. When your Department Head assigns work, it will show up here.
                </p>
            @else
                <ul class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($upcoming as $task)
                        @php $isOverdue = $task->deadline->lt(today()); @endphp
                        <li class="flex items-center justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate font-semibold">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->project->title }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-3 text-sm">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $priorityStyles[$task->priority] ?? $priorityStyles['medium'] }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <span class="{{ $isOverdue ? 'font-semibold text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ $task->deadline->format('d M Y') }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>