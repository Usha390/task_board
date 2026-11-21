<x-layouts.app title="Edit Employee">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-extrabold">Edit {{ $employee->name }}</h1>

        <form method="POST" action="{{ route('employees.update', $employee) }}"
              class="mt-6 space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @csrf
            @method('PUT')

            @include('employees._fields', ['employee' => $employee, 'departments' => $departments])

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="accent-gradient rounded-lg px-5 py-2 font-semibold text-white shadow-sm hover:opacity-90">
                    Update employee
                </button>
                <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>