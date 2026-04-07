<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>
    <x-slot name="subtitle">Login untuk membuka dashboard AI, riwayat chat, dan semua percakapan Anda.</x-slot>

    <x-auth-session-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-slate-600 dark:text-slate-300" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-none focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/40">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-between gap-4 pt-2">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-cyan-600 transition hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300">
                    Daftar
                </a>
            </p>

            <x-primary-button class="rounded-2xl border-none bg-[linear-gradient(135deg,#0f172a_0%,#0891b2_100%)] px-5 py-3 text-[11px] font-semibold tracking-[0.2em] text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,0.9)]">
                {{ __('Log In') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
