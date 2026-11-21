<x-layouts.app title="Add Employee">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-extrabold">Add employee</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            The employee will log in with this email and password. A Department Head can be appointed from the Employees list.
        </p>

        <form method="POST" action="{{ route('employees.store') }}"
              class="mt-6 space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @csrf

            @include('employees._fields', ['employee' => null, 'departments' => $departments])

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="accent-gradient rounded-lg px-5 py-2 font-semibold text-white shadow-sm hover:opacity-90">
                    Save employee
                </button>
                <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>