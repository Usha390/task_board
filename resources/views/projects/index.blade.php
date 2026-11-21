<x-layouts.app title="Projects">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold">Projects</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ auth()->user()->isDirector() ? 'All projects across the company.' : 'Projects assigned to your department.' }}
                </p>
            </div>

            @if (auth()->user()->isDirector())
                <a href="{{ route('projects.create') }}"
                   class="accent-gradient rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90">
                    + New project
                </a>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @if ($projects->isEmpty())
                <div class="py-10 text-center">
                    <div class="text-5xl">📁</div>
                    <p class="mt-4 font-semibold">No projects yet</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ auth()->user()->isDirector() ? 'Create the first project and assign it to a department.' : 'Projects assigned to your department will appear here.' }}
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="pb-3 font-medium">Project</th>
                                <th class="pb-3 font-medium">Department</th>
                                <th class="pb-3 font-medium">Deadline</th>
                                <th class="pb-3 font-medium">Progress</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($projects as $project)
                                @php
                                    $percent = $project->tasks_count > 0
                                        ? (int) round($project->done_tasks_count / $project->tasks_count * 100)
                                        : 0;
                                    $isOverdue = $project->status === 'active' && $project->deadline->lt(today());
                                @endphp

                                <tr>
                                    <td class="py-3 pr-4">
                                        <a href="{{ route('projects.show', $project) }}" class="font-semibold hover:underline">{{ $project->title }}</a>
                                    </td>
                                    <td class="py-3 pr-4">{{ $project->department->name }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="{{ $isOverdue ? 'font-semibold text-rose-600 dark:text-rose-400' : '' }}">
                                            {{ $project->deadline->format('d M Y') }}
                                        </span>
                                        @if ($isOverdue)
                                            <span class="ml-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-2 w-28 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ $project->done_tasks_count }}/{{ $project->tasks_count }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4">
                                        @if ($project->status === 'completed')
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Completed</span>
                                        @else
                                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">Active</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="font-semibold text-blue-600 hover:underline dark:text-blue-400">View</a>
                                            @if (auth()->user()->isDirector())
                                                <a href="{{ route('projects.edit', $project) }}"
                                                   class="font-semibold text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                            @endif
                                        </div>
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