<x-layouts.app title="Department Head Dashboard">
    <div class="space-y-6">
        <x-welcome-banner :subtitle="$subtitle" />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="Team members" :value="$stats['team']" icon="👥" color="sky" />
            <x-stat-card label="Active projects" :value="$stats['projects']" icon="📁" color="violet" />
            <x-stat-card label="Waiting for review" :value="$stats['review']" icon="🔍" color="amber" hint="Submissions that need your decision" />
            <x-stat-card label="Overdue tasks" :value="$stats['overdue']" icon="⏰" color="rose" hint="Past the deadline and not approved yet" />
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-status-bar :counts="$statusCounts" title="Department tasks" />
            <x-burnout-panel :flags="$atRisk" />
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <h2 class="text-lg font-bold">Waiting for your review</h2>

            @if ($reviewQueue->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    No submissions waiting. New submissions will appear here.
                </p>
            @else
                <ul class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($reviewQueue as $task)
                        <li class="flex items-center justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate font-semibold">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $task->project->title }} - submitted by {{ $task->assignee->name }}
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                                {{ $task->submitted_at ? $task->submitted_at->diffForHumans() : 'In review' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>