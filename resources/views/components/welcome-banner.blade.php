@props(['subtitle'])

@php
    $user = auth()->user();

    // Department Head ke naam er bodole role title dekhabo, baki der prothom naam
    $greetingName = $user->isHead()
        ? 'Department Head'
        : \Illuminate\Support\Str::before($user->name, ' ');
@endphp

<div class="accent-gradient relative overflow-hidden rounded-3xl p-8 text-white shadow-lg">
    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
    <div class="absolute -bottom-16 right-24 h-40 w-40 rounded-full bg-white/10"></div>

    <div class="relative">
        <h1 class="text-3xl font-extrabold">Welcome back, {{ $greetingName }}</h1>
        <p class="mt-2 max-w-xl text-white/90">{{ $subtitle }}</p>
    </div>
</div>