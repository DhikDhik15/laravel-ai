<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="AI Workspace" />

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.18),transparent_30%),linear-gradient(180deg,#020617_0%,#0f172a_100%)]"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between gap-4 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-300">AI Workspace</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-white">Laravel AI Assistant</h1>
                </div>

                <nav v-if="canLogin" class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition hover:border-white/20 hover:bg-white/10"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition hover:border-white/20 hover:bg-white/10"
                        >
                            Log In
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-full bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)] transition hover:-translate-y-0.5"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="flex flex-1 items-center py-8">
                <div class="grid w-full gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(380px,0.95fr)] lg:items-center">
                    <section class="max-w-3xl">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-300 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                            Chat, streaming, dan history dalam satu workspace
                        </div>

                        <h2 class="mt-6 text-5xl font-semibold tracking-tight text-white sm:text-6xl">
                            Bangun pengalaman AI internal yang terasa seperti produk jadi.
                        </h2>

                        <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                            Project ini sekarang sudah punya dashboard chat, riwayat percakapan, upload file, dark mode, dan streaming jawaban real-time. Halaman awal dan auth saya rapikan agar pengalaman masuknya juga terasa premium.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="rounded-2xl bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)] transition hover:-translate-y-0.5"
                            >
                                Buka Dashboard
                            </Link>
                            <template v-else>
                                <Link
                                    v-if="canLogin"
                                    :href="route('login')"
                                    class="rounded-2xl bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)] transition hover:-translate-y-0.5"
                                >
                                    Mulai Sekarang
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:border-white/20 hover:bg-white/10"
                                >
                                    Buat Akun
                                </Link>
                            </template>
                        </div>
                    </section>

                    <section class="grid gap-4">
                        <div class="rounded-[32px] border border-white/10 bg-white/95 p-6 text-slate-900 shadow-[0_40px_120px_-50px_rgba(15,23,42,0.75)] backdrop-blur dark:bg-slate-900/90 dark:text-slate-100">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-600 dark:text-cyan-400">What’s inside</p>
                            <div class="mt-5 grid gap-4">
                                <div class="rounded-[24px] border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-lg font-semibold">Realtime Chat</p>
                                    <p class="mt-1 text-sm leading-7 text-slate-600 dark:text-slate-400">Streaming SSE, bubble yang rapi, dan markdown rendering untuk jawaban AI.</p>
                                </div>
                                <div class="rounded-[24px] border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-lg font-semibold">History Sidebar</p>
                                    <p class="mt-1 text-sm leading-7 text-slate-600 dark:text-slate-400">Riwayat chat dikelompokkan per waktu dan menampilkan cuplikan isi percakapan terakhir.</p>
                                </div>
                                <div class="rounded-[24px] border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-lg font-semibold">Clean Auth Flow</p>
                                    <p class="mt-1 text-sm leading-7 text-slate-600 dark:text-slate-400">Login dan register sekarang lebih modern, ringan, dan selaras dengan dashboard.</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[28px] border border-white/10 bg-white/5 px-5 py-5 backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Stack</p>
                                <p class="mt-2 text-xl font-semibold text-white">Laravel {{ laravelVersion }}</p>
                                <p class="mt-1 text-sm text-slate-400">Dengan PHP {{ phpVersion }}</p>
                            </div>
                            <div class="rounded-[28px] border border-white/10 bg-white/5 px-5 py-5 backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Focus</p>
                                <p class="mt-2 text-xl font-semibold text-white">AI Assistant UI</p>
                                <p class="mt-1 text-sm text-slate-400">Cocok untuk dikembangkan jadi produk internal.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</template>
