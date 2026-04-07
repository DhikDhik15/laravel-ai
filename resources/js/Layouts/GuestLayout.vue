<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'Welcome Back',
    },
    subtitle: {
        type: String,
        default: 'Masuk ke workspace AI Anda dan lanjutkan percakapan dengan tampilan yang lebih rapi.',
    },
    eyebrow: {
        type: String,
        default: 'AI Workspace',
    },
});
</script>

<template>
    <Head :title="title" />

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(59,130,246,0.18),transparent_30%),linear-gradient(180deg,#020617_0%,#0f172a_100%)]"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)] lg:items-center">
                <section class="hidden lg:block">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-300 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                            {{ eyebrow }}
                        </div>

                        <h1 class="mt-6 text-5xl font-semibold tracking-tight text-white">
                            Ruang kerja AI yang lebih tenang, cepat, dan terasa matang.
                        </h1>

                        <p class="mt-5 max-w-xl text-base leading-8 text-slate-300">
                            Masuk untuk membuka riwayat chat, streaming jawaban real-time, dan workspace yang sudah disesuaikan dengan light dan dark mode.
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
                            <Link href="/" class="inline-flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                    <ApplicationLogo class="h-8 w-8 fill-current text-slate-700 dark:text-slate-100" />
                                </span>
                                <span>
                                    <span class="block text-xs font-semibold uppercase tracking-[0.24em] text-cyan-600 dark:text-cyan-400">{{ eyebrow }}</span>
                                    <span class="mt-1 block text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ title }}</span>
                                </span>
                            </Link>

                            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                {{ subtitle }}
                            </p>
                        </div>

                        <div class="px-6 py-6 sm:px-7 sm:py-7">
                            <slot />
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
