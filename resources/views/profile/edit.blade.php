<x-app-layout>
    <style>
        .profile-shell {
            padding: 1.25rem 1rem 2rem;
            background: var(--app-bg);
        }

        .profile-grid {
            display: grid;
            gap: 1rem;
        }

        .profile-panel,
        .profile-stat,
        .profile-input-wrap {
            background: var(--app-surface-strong) !important;
            color: var(--app-text) !important;
            border: 1px solid var(--app-border);
        }

        .profile-panel {
            border-radius: 28px;
            box-shadow: 0 20px 60px -40px rgba(15, 23, 42, 0.35);
        }

        .profile-stat {
            border-radius: 24px;
            padding: 1rem 1.1rem;
        }

        .profile-muted {
            color: var(--app-text-soft) !important;
        }

        .profile-input {
            width: 100%;
            border: none !important;
            background: transparent !important;
            color: var(--app-text) !important;
            padding: 0.25rem 0;
            outline: none;
            box-shadow: none !important;
        }

        .profile-input-wrap {
            border-radius: 22px;
            padding: 0.95rem 1.1rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .profile-input-wrap:focus-within {
            border-color: rgba(8, 145, 178, 0.55);
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.12);
        }

        .profile-button {
            background: linear-gradient(135deg, #0f172a 0%, #0891b2 100%) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 18px !important;
            box-shadow: 0 18px 40px -18px rgba(8, 145, 178, 0.9);
        }

        .profile-danger {
            background: linear-gradient(135deg, #7f1d1d 0%, #e11d48 100%) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 18px !important;
        }

        .profile-warning {
            border-radius: 22px;
            border: 1px solid rgba(251, 191, 36, 0.35);
            background: rgba(251, 191, 36, 0.09);
        }

        .profile-danger-box {
            border-radius: 22px;
            border: 1px solid rgba(244, 63, 94, 0.2);
            background: rgba(244, 63, 94, 0.08);
        }

        @media (min-width: 1024px) {
            .profile-shell {
                padding: 1.25rem 2rem 2rem;
            }

            .profile-layout {
                display: grid;
                grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
                gap: 1rem;
                align-items: start;
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">Profile Settings</h2>
                <p class="mt-1 text-sm profile-muted">Kelola akun, password, dan keamanan dengan tampilan yang selaras dengan dashboard.</p>
            </div>
        </div>
    </x-slot>

    <div class="profile-shell">
        <div class="mx-auto max-w-7xl">
            <div class="profile-grid">
                <section class="profile-panel overflow-hidden p-6 sm:p-7">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-600 dark:text-cyan-400">Account Center</p>
                            <h3 class="mt-2 text-3xl font-semibold tracking-tight">Semua pengaturan akun ada di satu ruang yang lebih bersih.</h3>
                            <p class="mt-3 text-sm leading-7 profile-muted">
                                Halaman profile sekarang mengikuti pola visual dashboard: panel lebih lembut, jarak lebih lega, dan komponen lebih konsisten di light maupun dark mode.
                            </p>
                        </div>

                        <div class="grid min-w-0 gap-3 sm:grid-cols-3 lg:w-[340px] lg:grid-cols-1">
                            <div class="profile-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] profile-muted">Status</p>
                                <p class="mt-2 text-lg font-semibold">Akun aktif</p>
                                <p class="mt-1 text-sm profile-muted">{{ auth()->user()->email_verified_at ? 'Email terverifikasi' : 'Perlu verifikasi email' }}</p>
                            </div>
                            <div class="profile-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] profile-muted">Nama</p>
                                <p class="mt-2 truncate text-lg font-semibold">{{ auth()->user()->name }}</p>
                                <p class="mt-1 text-sm profile-muted">Profil utama yang dipakai saat ini</p>
                            </div>
                            <div class="profile-stat">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] profile-muted">Keamanan</p>
                                <p class="mt-2 text-lg font-semibold">Password</p>
                                <p class="mt-1 text-sm profile-muted">Bisa diperbarui kapan saja dari bawah</p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="profile-layout">
                    <section class="profile-panel overflow-hidden">
                        <div class="border-b px-6 py-5 sm:px-7" style="border-color: var(--app-border);">
                            <h3 class="text-xl font-semibold">Informasi Profil</h3>
                            <p class="mt-1 text-sm profile-muted">Perbarui nama dan email akun Anda.</p>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-5 px-6 py-6 sm:px-7 sm:py-7">
                            @csrf
                            @method('patch')

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="name" :value="__('Name')" class="text-sm font-medium profile-muted" />
                                    <div class="profile-input-wrap mt-2">
                                        <x-text-input id="name" name="name" type="text" class="profile-input" :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="text-sm font-medium profile-muted" />
                                    <div class="profile-input-wrap mt-2">
                                        <x-text-input id="email" name="email" type="email" class="profile-input" :value="old('email', auth()->user()->email)" required autocomplete="username" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                            </div>

                            @if ($mustVerifyEmail && auth()->user()->email_verified_at === null)
                                <div class="profile-warning px-5 py-4 text-sm text-amber-900 dark:text-amber-200">
                                    <p>Email Anda belum terverifikasi.</p>
                                    <button type="submit" form="send-verification" class="mt-3 font-semibold underline underline-offset-4">
                                        Kirim ulang email verifikasi
                                    </button>

                                    @if ($status === 'verification-link-sent')
                                        <p class="mt-3 font-medium text-emerald-700 dark:text-emerald-300">
                                            Link verifikasi baru sudah dikirim ke email Anda.
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center gap-3">
                                <x-primary-button class="profile-button px-5 py-3 text-[11px] font-semibold tracking-[0.2em]">
                                    Save Profile
                                </x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Perubahan profil berhasil disimpan.</p>
                                @endif
                            </div>
                        </form>

                        @if ($mustVerifyEmail && auth()->user()->email_verified_at === null)
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                                @csrf
                            </form>
                        @endif
                    </section>

                    <div class="profile-grid">
                        <section class="profile-panel overflow-hidden">
                            <div class="border-b px-6 py-5 sm:px-7" style="border-color: var(--app-border);">
                                <h3 class="text-xl font-semibold">Update Password</h3>
                                <p class="mt-1 text-sm profile-muted">Gunakan password yang kuat agar akun tetap aman.</p>
                            </div>

                            <form method="post" action="{{ route('password.update') }}" class="space-y-5 px-6 py-6 sm:px-7 sm:py-7">
                                @csrf
                                @method('put')

                                <div>
                                    <x-input-label for="current_password" :value="__('Current Password')" class="text-sm font-medium profile-muted" />
                                    <div class="profile-input-wrap mt-2">
                                        <x-text-input id="current_password" name="current_password" type="password" class="profile-input" autocomplete="current-password" />
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('New Password')" class="text-sm font-medium profile-muted" />
                                    <div class="profile-input-wrap mt-2">
                                        <x-text-input id="password" name="password" type="password" class="profile-input" autocomplete="new-password" />
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium profile-muted" />
                                    <div class="profile-input-wrap mt-2">
                                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="profile-input" autocomplete="new-password" />
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-primary-button class="profile-button px-5 py-3 text-[11px] font-semibold tracking-[0.2em]">
                                        Update Password
                                    </x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Password berhasil diperbarui.</p>
                                    @endif
                                </div>
                            </form>
                        </section>

                        <section class="profile-panel overflow-hidden">
                            <div class="border-b px-6 py-5 sm:px-7" style="border-color: var(--app-border);">
                                <h3 class="text-xl font-semibold text-rose-700 dark:text-rose-300">Delete Account</h3>
                                <p class="mt-1 text-sm profile-muted">Tindakan ini permanen. Semua data akun akan terhapus.</p>
                            </div>

                            <div class="space-y-5 px-6 py-6 sm:px-7 sm:py-7">
                                <div class="profile-danger-box px-5 py-4 text-sm leading-7 text-rose-800 dark:text-rose-200">
                                    Menghapus akun akan menghapus akses dan data yang terhubung. Pastikan keputusan ini memang final sebelum melanjutkan.
                                </div>

                                <x-danger-button
                                    x-data
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                    class="profile-danger px-5 py-3 text-[11px] font-semibold tracking-[0.2em]"
                                >
                                    Delete Account
                                </x-danger-button>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6 bg-white p-6 dark:bg-slate-900">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus akun secara permanen?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                    Masukkan password Anda untuk mengonfirmasi penghapusan akun.
                </p>
            </div>

            <div>
                <x-input-label for="delete_password" :value="__('Password')" class="sr-only" />
                <div class="profile-input-wrap">
                    <x-text-input
                        id="delete_password"
                        name="password"
                        type="password"
                        class="profile-input"
                        placeholder="Password"
                    />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-user-deletion')" class="rounded-2xl border-slate-300 px-5 py-3 text-[11px] tracking-[0.2em] text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800">
                    Cancel
                </x-secondary-button>

                <x-danger-button class="profile-danger px-5 py-3 text-[11px] font-semibold tracking-[0.2em]">
                    Delete Account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
