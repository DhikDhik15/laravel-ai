@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-cyan-500 bg-cyan-50 ps-3 pe-4 py-2 text-start text-base font-semibold text-cyan-700 transition duration-150 ease-in-out focus:outline-none dark:bg-cyan-950/30 dark:text-cyan-300'
            : 'block w-full border-l-4 border-transparent ps-3 pe-4 py-2 text-start text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 focus:outline-none dark:text-slate-300 dark:hover:bg-slate-900/70 dark:hover:text-white dark:hover:border-slate-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
