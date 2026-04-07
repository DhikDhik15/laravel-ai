<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(59,130,246,0.18),transparent_30%),linear-gradient(180deg,#020617_0%,#0f172a_100%)]"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid w-full gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)] lg:items-center">
                    <section class="hidden lg:block">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-300 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                                AI Workspace
                            </div>

                            <h1 class="mt-6 text-5xl font-semibold tracking-tight text-white">
                                Masuk ke workspace AI yang terasa lebih matang.
                            </h1>

                            <p class="mt-5 max-w-xl text-base leading-8 text-slate-300">
                                Halaman auth sekarang mengikuti arah visual dashboard: lebih bersih, lebih modern, dan tetap nyaman dipakai untuk login maupun registrasi.
                            </p>

                            <div class="mt-8 grid max-w-xl gap-4 sm:grid-cols-3">
                                <div class="rounded-[28px] border border-white/10 bg-white/5 px-4 py-4 backdrop-blur">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Realtime</p>
                                    <p class="mt-2 text-lg font-semibold text-white">SSE Chat</p>
                                    <p class="mt-1 text-sm text-slate-400">Jawaban tampil bertahap dan lebih natural.</p>
                                </div>
                                <div class="rounded-[28px] border border-white/10 bg-white/5 px-4 py-4 backdrop-blur">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">History</p>
                                    <p class="mt-2 text-lg font-semibold text-white">Sidebar</p>
                                    <p class="mt-1 text-sm text-slate-400">Semua percakapan tertata dan mudah dicari.</p>
                                </div>
                                <div class="rounded-[28px] border border-white/10 bg-white/5 px-4 py-4 backdrop-blur">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Files</p>
                                    <p class="mt-2 text-lg font-semibold text-white">Context</p>
                                    <p class="mt-1 text-sm text-slate-400">Lampiran dokumen ikut masuk ke alur chat.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="w-full">
                        <div class="overflow-hidden rounded-[32px] border border-white/10 bg-white/95 shadow-[0_40px_120px_-50px_rgba(15,23,42,0.75)] backdrop-blur dark:bg-slate-900/90">
                            <div class="border-b border-slate-200/70 px-6 py-6 dark:border-slate-800">
                                <a href="/" class="inline-flex items-center gap-3">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                        <x-application-logo class="h-8 w-8 fill-current text-slate-700 dark:text-slate-100" />
                                    </span>
                                    <span>
                                        <span class="block text-xs font-semibold uppercase tracking-[0.24em] text-cyan-600 dark:text-cyan-400">{{ $eyebrow ?? 'AI Workspace' }}</span>
                                        <span class="mt-1 block text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $title ?? 'Welcome' }}</span>
                                    </span>
                                </a>

                                @isset($subtitle)
                                    <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                        {{ $subtitle }}
                                    </p>
                                @endisset
                            </div>

                            <div class="px-6 py-6 sm:px-7 sm:py-7">
                                {{ $slot }}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
