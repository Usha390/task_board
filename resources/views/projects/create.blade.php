<x-layouts.app title="New Project">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-extrabold">New project</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            The Department Head will divide this project into tasks and assign them to employees.
        </p>

        <form method="POST" action="{{ route('projects.store') }}"
              class="mt-6 space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @csrf

            @include('projects._fields', ['project' => null, 'departments' => $departments])

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="accent-gradient rounded-lg px-5 py-2 font-semibold text-white shadow-sm hover:opacity-90">
                    Create project
                </button>
                <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>