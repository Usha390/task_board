@php
    $isEdit = $project !== null;
    $hasTasks = $hasTasks ?? false;
    $inputClass = 'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
@endphp

<div>
    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project title</label>
    <input id="title" type="text" name="title" value="{{ old('title', $project?->title) }}" required maxlength="150"
           class="{{ $inputClass }}">
    @error('title')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (optional)</label>
    <textarea id="description" name="description" rows="4" maxlength="2000"
              class="{{ $inputClass }}">{{ old('description', $project?->description) }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>

    @if ($hasTasks)
        <p class="mt-1 rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-gray-700/50">
            {{ $project->department?->name }}
            <span class="block text-xs text-gray-500 dark:text-gray-400">
                This project already has tasks, so its department cannot be changed.
            </span>
        </p>
    @else
        <select id="department_id" name="department_id" required class="{{ $inputClass }}">
            <option value="">Select a department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    @selected((string) old('department_id', $project?->department_id) === (string) $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Only departments that have a Head are listed.</p>
        @error('department_id')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    @endif
</div>

<div>
    <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
    <input id="deadline" type="date" name="deadline" value="{{ old('deadline', $project?->deadline?->format('Y-m-d')) }}" required
           @if (! $isEdit) min="{{ today()->toDateString() }}" @endif
           class="{{ $inputClass }}">
    @error('deadline')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
