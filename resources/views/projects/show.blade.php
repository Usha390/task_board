@php
    $statusStyles = [
        'todo' => ['label' => 'To Do', 'class' => 'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-300'],
        'in_progress' => ['label' => 'In Progress', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300'],
        'review' => ['label' => 'In Review', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300'],
        'done' => ['label' => 'Done', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'],
    ];

    $priorityStyles = [
        'high' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
        'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
        'low' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300',
    ];

    $total = $tasks->count();
    $done = $tasks->where('status', 'done')->count();
    $percent = $total > 0 ? (int) round($done / $total * 100) : 0;
    $isOverdue = $project->status === 'active' && $project->deadline->lt(today());
@endphp

<x-layouts.app :title="$project->title">
    <div class="space-y-6">
        <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Back to projects</a>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-extrabold">{{ $project->title }}</h1>

                        @if ($project->status === 'completed')
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Completed</span>
                        @else
                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">Active</span>
                        @endif

                        @if ($isOverdue)
                            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">Overdue</span>
                        @endif
                    </div>

                    @if ($project->description)
                        <p class="mt-3 max-w-2xl whitespace-pre-line text-sm text-gray-600 dark:text-gray-300">{{ $project->description }}</p>
                    @endif
                </div>

                @if (auth()->user()->isDirector())
                    <a href="{{ route('projects.edit', $project) }}"
                       class="accent-gradient rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90">
                        Edit project
                    </a>
                @endif
            </div>

            <dl class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl bg-violet-50 p-4 ring-1 ring-violet-200 dark:bg-violet-500/10 dark:ring-violet-500/30">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Department</dt>
                    <dd class="mt-1 font-bold text-violet-700 dark:text-violet-300">{{ $project->department->name }}</dd>
                </div>
                <div class="rounded-xl bg-teal-50 p-4 ring-1 ring-teal-200 dark:bg-teal-500/10 dark:ring-teal-500/30">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Department Head</dt>
                    <dd class="mt-1 font-bold text-teal-700 dark:text-teal-300">{{ $project->department->head?->name ?? 'Not appointed' }}</dd>
                </div>
                <div class="rounded-xl p-4 ring-1 {{ $isOverdue ? 'bg-rose-50 ring-rose-200 dark:bg-rose-500/10 dark:ring-rose-500/30' : 'bg-amber-50 ring-amber-200 dark:bg-amber-500/10 dark:ring-amber-500/30' }}">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Deadline</dt>
                    <dd class="mt-1 font-bold {{ $isOverdue ? 'text-rose-700 dark:text-rose-300' : 'text-amber-700 dark:text-amber-300' }}">
                        {{ $project->deadline->format('d M Y') }}
                    </dd>
                </div>
                <div class="rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:ring-emerald-500/30">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Approved tasks</dt>
                    <dd class="mt-1 font-bold text-emerald-700 dark:text-emerald-300">{{ $done }} of {{ $total }} ({{ $percent }}%)</dd>
                </div>
            </dl>

            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                Created by {{ $project->creator->name }} on {{ $project->created_at->format('d M Y') }}
            </p>
        </div>

        <x-status-bar :counts="$statusCounts" title="Task progress" />

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <h2 class="text-lg font-bold">Tasks</h2>

            @if ($tasks->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    No tasks yet. The Department Head will divide this project into tasks.
                </p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="pb-3 font-medium">Task</th>
                                <th class="pb-3 font-medium">Assigned to</th>
                                <th class="pb-3 font-medium">Priority</th>
                                <th class="pb-3 font-medium">Deadline</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($tasks as $task)
                                @php
                                    $taskOverdue = $task->status !== 'done' && $task->deadline->lt(today());
                                    $badge = $statusStyles[$task->status] ?? $statusStyles['todo'];
                                @endphp
                                <tr>
                                    <td class="py-3 pr-4 font-semibold">{{ $task->title }}</td>
                                    <td class="py-3 pr-4">{{ $task->assignee->name }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $priorityStyles[$task->priority] ?? $priorityStyles['medium'] }}">{{ ucfirst($task->priority) }}</span>
                                    </td>
                                    <td class="py-3 pr-4 {{ $taskOverdue ? 'font-semibold text-rose-600 dark:text-rose-400' : '' }}">
                                        {{ $task->deadline->format('d M Y') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>