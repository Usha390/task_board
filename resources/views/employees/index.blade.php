<x-layouts.app title="Employees">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold">Employees</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add employees, appoint Department Heads and manage who can log in.</p>
            </div>
            <a href="{{ route('employees.create') }}"
               class="accent-gradient rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90">
                + Add employee
            </a>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @if ($employees->isEmpty())
                <div class="py-10 text-center">
                    <div class="text-5xl">👥</div>
                    <p class="mt-4 font-semibold">No employees yet</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a department, then add your first employee.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="pb-3 font-medium">Name</th>
                                <th class="pb-3 font-medium">Department</th>
                                <th class="pb-3 font-medium">Role</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($employees as $employee)
                                @php
                                    $currentHead = $employee->department?->head;

                                    $confirmText = $currentHead
                                        ? "Make {$employee->name} the Head of {$employee->department?->name}? {$currentHead->name} will become a regular employee."
                                        : "Make {$employee->name} the Head of {$employee->department?->name}?";

                                    $statusText = $employee->is_active
                                        ? "Deactivate {$employee->name}? They will not be able to log in."
                                        : "Activate {$employee->name} again?";
                                @endphp

                                <tr class="{{ $employee->is_active ? '' : 'opacity-60' }}">
                                    <td class="py-3 pr-4">
                                        <p class="font-semibold">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->email }}</p>
                                    </td>
                                    <td class="py-3 pr-4">{{ $employee->department?->name ?? 'No department' }}</td>
                                    <td class="py-3 pr-4">
                                        @if ($employee->isHead())
                                            <span class="rounded-full bg-teal-100 px-2.5 py-1 text-xs font-semibold text-teal-700 dark:bg-teal-500/20 dark:text-teal-300">Head</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-500/20 dark:text-slate-300">Employee</span>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        @if ($employee->is_active)
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Active</span>
                                        @else
                                            <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-3">
                                            <a href="{{ route('employees.edit', $employee) }}"
                                               class="font-semibold text-blue-600 hover:underline dark:text-blue-400">Edit</a>

                                            @if ($employee->isEmployee() && $employee->is_active && $employee->department)
                                                <form method="POST" action="{{ route('employees.appoint-head', $employee) }}"
                                                      onsubmit='return confirm(@json($confirmText))'>
                                                    @csrf
                                                    <button type="submit" class="font-semibold text-teal-600 hover:underline dark:text-teal-400">
                                                        Appoint as Head
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('employees.status', $employee) }}"
                                                  onsubmit='return confirm(@json($statusText))'>
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="font-semibold hover:underline {{ $employee->is_active ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                    {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
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