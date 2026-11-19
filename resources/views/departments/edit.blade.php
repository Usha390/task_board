<x-layouts.app title="Edit Department">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-extrabold">Edit department</h1>

        <form method="POST" action="{{ route('departments.update', $department) }}"
              class="mt-6 space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            @csrf
            @method('PUT')

            @include('departments._fields', ['department' => $department])

            <div class="rounded-xl bg-gray-50 p-4 text-sm dark:bg-gray-700/50">
                <span class="font-semibold">Department Head:</span>
                {{ $department->head?->name ?? 'Not appointed yet' }}
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The Head is appointed from the Employees page.</p>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="accent-gradient rounded-lg px-5 py-2 font-semibold text-white shadow-sm hover:opacity-90">
                    Update department
                </button>
                <a href="{{ route('departments.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>