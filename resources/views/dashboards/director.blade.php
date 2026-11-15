<x-layouts.app title="Director Dashboard">
    <div class="space-y-6">
        <x-welcome-banner subtitle="Here is how your departments, projects and teams are doing." />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="Departments" :value="$stats['departments']" icon="🏢" color="violet" />
            <x-stat-card label="Active employees" :value="$stats['employees']" icon="👥" color="sky" />
            <x-stat-card label="Active projects" :value="$stats['projects']" icon="📁" color="emerald" />
            <x-stat-card label="Overdue tasks" :value="$stats['overdue']" icon="⏰" color="rose" hint="Past the deadline and not approved yet" />
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-status-bar :counts="$statusCounts" title="All tasks" />
            <x-burnout-panel :flags="$atRisk" />
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <h2 class="text-lg font-bold">Departments</h2>

            @if ($departments->isEmpty())
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    No departments yet. Add your first department to get started.
                </p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="pb-3 font-medium">Department</th>
                                <th class="pb-3 font-medium">Head</th>
                                <th class="pb-3 text-right font-medium">People</th>
                                <th class="pb-3 text-right font-medium">Projects</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($departments as $department)
                                <tr>
                                    <td class="py-3 font-semibold">{{ $department->name }}</td>
                                    <td class="py-3">{{ $department->head?->name ?? 'Not assigned' }}</td>
                                    <td class="py-3 text-right">{{ $department->employees_count }}</td>
                                    <td class="py-3 text-right">{{ $department->projects_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>