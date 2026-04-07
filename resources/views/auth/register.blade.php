<x-guest-layout>
    <x-slot name="title">Buat Akun</x-slot>
    <x-slot name="subtitle">Daftar untuk mulai memakai AI workspace dengan history chat, upload file, dan tampilan yang lebih modern.</x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="name" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4 pt-2">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300">
                    Masuk
                </a>
            </p>

            <x-primary-button class="rounded-2xl border-none bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-[11px] font-semibold tracking-[0.2em] text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)]">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
