@php
    $user = auth()->user();

    $menus = [
        'director' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'director.dashboard'],
            ['label' => 'Departments', 'icon' => '🏢', 'route' => 'departments.index'],
            ['label' => 'Employees', 'icon' => '👥', 'route' => 'employees.index'],
            ['label' => 'Projects', 'icon' => '📁', 'route' => 'projects.index'],
        ],
        'head' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'head.dashboard'],
            ['label' => 'Projects', 'icon' => '📁', 'route' => 'projects.index'],
            ['label' => 'Tasks', 'icon' => '✅', 'route' => 'tasks.index'],
            ['label' => 'Kanban Board', 'icon' => '🗂️', 'route' => 'board.index'],
            ['label' => 'Review Queue', 'icon' => '🔍', 'route' => 'reviews.index'],
        ],
        'employee' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'employee.dashboard'],
            ['label' => 'My Tasks', 'icon' => '✅', 'route' => 'tasks.index'],
            ['label' => 'My Board', 'icon' => '🗂️', 'route' => 'board.index'],
        ],
    ];

    $items = $menus[$user->role] ?? [];
@endphp

<aside id="sidebar" class="accent-gradient hidden w-full shrink-0 text-white shadow-lg md:flex md:w-64 md:flex-col">
    <div class="flex items-center gap-3 px-6 py-6">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-xl">📋</span>
        <span class="text-xl font-extrabold tracking-tight">{{ config('app.name') }}</span>
    </div>

    <nav class="flex-1 space-y-1 px-4 pb-4">
        @foreach ($items as $item)
            @if (Route::has($item['route']))
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'accent-ink bg-white shadow-sm' : 'text-white/90 hover:bg-white/15' }}">
                    <span class="text-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @else
                <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-white/50" title="Coming soon">
                    <span class="text-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </nav>

    <div class="m-4 rounded-xl bg-white/15 p-4 text-sm">
        <div class="font-semibold">{{ ucfirst($user->role) }}</div>
        @if ($user->department)
            <div class="text-white/80">{{ $user->department->name }}</div>
        @endif
    </div>
</aside>