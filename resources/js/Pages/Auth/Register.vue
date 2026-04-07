<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout
        title="Buat Akun"
        eyebrow="AI Workspace"
        subtitle="Daftar untuk mulai memakai AI workspace dengan history chat, upload file, dan tampilan yang lebih modern."
    >
        <Head title="Register" />

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel
                    for="name"
                    value="Name"
                    class="text-sm font-medium text-slate-600 dark:text-slate-300"
                />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

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
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                    class="text-sm font-medium text-slate-600 dark:text-slate-300"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-between gap-4 pt-2">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Sudah punya akun?
                    <Link
                        :href="route('login')"
                        class="font-semibold text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300"
                    >
                        Masuk
                    </Link>
                </p>
                <PrimaryButton
                    class="rounded-2xl border-none bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-[11px] font-semibold tracking-[0.2em] text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
