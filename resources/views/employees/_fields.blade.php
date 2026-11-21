@php
    $isEdit = $employee !== null;
    $inputClass = 'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
@endphp

<div>
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full name</label>
    <input id="name" type="text" name="name" value="{{ old('name', $employee?->name) }}" required maxlength="100"
           class="{{ $inputClass }}">
    @error('name')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (used to log in)</label>
    <input id="email" type="email" name="email" value="{{ old('email', $employee?->email) }}" required maxlength="191"
           class="{{ $inputClass }}">
    @error('email')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>

    @if ($isEdit && $employee->isHead())
        <p class="mt-1 rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-gray-700/50">
            {{ $employee->department?->name }}
            <span class="block text-xs text-gray-500 dark:text-gray-400">
                This person is the Department Head. Appoint another Head before changing the department.
            </span>
        </p>
    @else
        <select id="department_id" name="department_id" required class="{{ $inputClass }}">
            <option value="">Select a department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    @selected((string) old('department_id', $employee?->department_id) === (string) $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    @endif
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $isEdit ? 'New password (optional)' : 'Password' }}
        </label>
        <input id="password" type="password" name="password" minlength="8" autocomplete="new-password"
               {{ $isEdit ? '' : 'required' }} class="{{ $inputClass }}">
        @error('password')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password"
               {{ $isEdit ? '' : 'required' }} class="{{ $inputClass }}">
    </div>
</div>

@if ($isEdit)
    <p class="text-xs text-gray-500 dark:text-gray-400">Leave both password fields empty to keep the current password.</p>
@endif