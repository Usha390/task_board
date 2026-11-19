@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="en" data-role="{{ auth()->user()->role }}" class="{{ auth()->user()->theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script>
        localStorage.setItem('theme', '{{ auth()->user()->theme }}');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen flex-col md:flex-row">

        <x-sidebar />

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="flex items-center justify-between bg-white px-6 py-3 shadow-sm dark:bg-gray-800">
                <button id="sidebar-toggle" type="button"
                        class="rounded-lg border border-gray-300 px-3 py-1 text-sm md:hidden dark:border-gray-600">
                    Menu
                </button>

                <div class="ml-auto flex items-center gap-4">
                    <button id="theme-toggle" type="button" data-url="{{ route('theme.update') }}"
                            class="rounded-lg border border-gray-300 px-3 py-1 text-sm hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700"
                            aria-label="Toggle dark mode">
                        <span class="dark:hidden">🌙 Dark</span>
                        <span class="hidden dark:inline">☀️ Light</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="accent-gradient flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-white">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden text-right text-sm sm:block">
                            <div class="font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1 text-sm font-medium text-white hover:bg-rose-700">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

                        {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm font-medium text-emerald-800 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/30">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>