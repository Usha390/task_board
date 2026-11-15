@props(['label', 'value', 'icon' => '📊', 'color' => 'slate', 'hint' => null])

@php
    $styles = [
        'violet' => ['tile' => 'bg-violet-50 ring-violet-200 dark:bg-violet-500/10 dark:ring-violet-500/30', 'icon' => 'bg-violet-500', 'value' => 'text-violet-700 dark:text-violet-300'],
        'sky' => ['tile' => 'bg-sky-50 ring-sky-200 dark:bg-sky-500/10 dark:ring-sky-500/30', 'icon' => 'bg-sky-500', 'value' => 'text-sky-700 dark:text-sky-300'],
        'emerald' => ['tile' => 'bg-emerald-50 ring-emerald-200 dark:bg-emerald-500/10 dark:ring-emerald-500/30', 'icon' => 'bg-emerald-500', 'value' => 'text-emerald-700 dark:text-emerald-300'],
        'amber' => ['tile' => 'bg-amber-50 ring-amber-200 dark:bg-amber-500/10 dark:ring-amber-500/30', 'icon' => 'bg-amber-500', 'value' => 'text-amber-700 dark:text-amber-300'],
        'rose' => ['tile' => 'bg-rose-50 ring-rose-200 dark:bg-rose-500/10 dark:ring-rose-500/30', 'icon' => 'bg-rose-500', 'value' => 'text-rose-700 dark:text-rose-300'],
        'blue' => ['tile' => 'bg-blue-50 ring-blue-200 dark:bg-blue-500/10 dark:ring-blue-500/30', 'icon' => 'bg-blue-500', 'value' => 'text-blue-700 dark:text-blue-300'],
        'slate' => ['tile' => 'bg-white ring-slate-200 dark:bg-slate-500/10 dark:ring-slate-500/30', 'icon' => 'bg-slate-500', 'value' => 'text-slate-700 dark:text-slate-300'],
    ];

    $s = $styles[$color] ?? $styles['slate'];
@endphp

<div class="rounded-2xl p-5 ring-1 {{ $s['tile'] }}">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</p>
            <p class="mt-1 text-3xl font-extrabold {{ $s['value'] }}">{{ $value }}</p>
        </div>
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-2xl {{ $s['icon'] }}">
            {{ $icon }}
        </div>
    </div>

    @if ($hint)
        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>