<div>
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department name</label>
    <input id="name" type="text" name="name" value="{{ old('name', $department?->name) }}" required maxlength="100"
           class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
    @error('name')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (optional)</label>
    <textarea id="description" name="description" rows="3" maxlength="500"
              class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $department?->description) }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>