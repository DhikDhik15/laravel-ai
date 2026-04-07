<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout
        title="Masuk"
        eyebrow="AI Workspace"
        subtitle="Login untuk membuka dashboard AI, riwayat chat, dan semua percakapan Anda."
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel
                    for="email"
                    value="Email"
                    class="text-sm font-medium text-slate-600 dark:text-slate-300"
                />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel
                    for="password"
                    value="Password"
                    class="text-sm font-medium text-slate-600 dark:text-slate-300"
                />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/40">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-slate-600 dark:text-slate-300"
                        >Remember me</span
                    >
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300"
                >
                    Forgot password?
                </Link>
            </div>

            <div class="flex items-center justify-between gap-4 pt-2">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Belum punya akun?
                    <Link
                        :href="route('register')"
                        class="font-semibold text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300"
                    >
                        Daftar
                    </Link>
                </p>
                <PrimaryButton
                    class="rounded-2xl border-none bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-[11px] font-semibold tracking-[0.2em] text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log In
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
