<x-layouts.app title="Departments">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold">Departments</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create and manage the departments of your company.</p>
            </div>
            <a href="{{ route('departments.create') }}"
               class="accent-gradient rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90">
                + Add department
            </a>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @if ($departments->isEmpty())
                <div class="py-10 text-center">
                    <div class="text-5xl">🏢</div>
                    <p class="mt-4 font-semibold">No departments yet</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add your first department to get started.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="pb-3 font-medium">Department</th>
                                <th class="pb-3 font-medium">Head</th>
                                <th class="pb-3 text-right font-medium">People</th>
                                <th class="pb-3 text-right font-medium">Projects</th>
                                <th class="pb-3 text-right font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($departments as $department)
                                <tr>
                                    <td class="py-3 pr-4">
                                        <p class="font-semibold">{{ $department->name }}</p>
                                        @if ($department->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $department->description }}</p>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        @if ($department->head)
                                            {{ $department->head->name }}
                                        @else
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">Not appointed</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">{{ $department->employees_count }}</td>
                                    <td class="py-3 text-right">{{ $department->projects_count }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('departments.edit', $department) }}"
                                           class="font-semibold text-blue-600 hover:underline dark:text-blue-400">Edit</a>
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