@props(['flags'])

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-xl dark:bg-rose-500/20">🔥</span>
        <div>
            <h2 class="text-lg font-bold">Burnout alerts</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Team members with too many tight deadlines.</p>
        </div>
    </div>

    @if ($flags->isEmpty())
        <div class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
            Nobody is overloaded right now. Workloads look balanced.
        </div>
    @else
        <ul class="mt-5 divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($flags as $flag)
                <li class="flex items-center justify-between gap-3 py-3">
                    <div>
                        <p class="font-semibold">{{ $flag->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $flag->user->department?->name ?? 'No department' }}</p>
                    </div>
                    <div class="flex flex-wrap justify-end gap-2 text-xs font-semibold">
                        <span class="rounded-full bg-rose-100 px-2.5 py-1 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">{{ $flag->pending_count }} pending</span>
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">{{ $flag->due_soon_count }} due soon</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>