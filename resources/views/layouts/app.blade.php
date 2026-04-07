<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            (() => {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const shouldUseDark = storedTheme ? storedTheme === 'dark' : prefersDark;

                document.documentElement.classList.toggle('dark', shouldUseDark);
                document.documentElement.style.colorScheme = shouldUseDark ? 'dark' : 'light';
            })();
        </script>

        <style>
            :root {
                --app-bg: #f8fafc;
                --app-surface: rgba(255, 255, 255, 0.92);
                --app-surface-strong: #ffffff;
                --app-text: #0f172a;
                --app-text-soft: #475569;
                --app-border: rgba(148, 163, 184, 0.22);
            }

            html.dark {
                --app-bg: #020617;
                --app-surface: rgba(15, 23, 42, 0.88);
                --app-surface-strong: #0f172a;
                --app-text: #e2e8f0;
                --app-text-soft: #94a3b8;
                --app-border: rgba(51, 65, 85, 0.45);
            }

            body {
                background: var(--app-bg) !important;
                color: var(--app-text) !important;
            }

            .app-shell {
                background: var(--app-bg) !important;
            }

            .app-header {
                background: var(--app-surface) !important;
                border-bottom: 1px solid var(--app-border);
                position: relative;
                z-index: 10;
            }

            .app-panel {
                background: var(--app-surface-strong) !important;
                color: var(--app-text) !important;
                border: 1px solid var(--app-border);
                border-radius: 28px;
                box-shadow: 0 20px 60px -40px rgba(15, 23, 42, 0.35);
            }

            .app-panel-soft {
                background: var(--app-surface) !important;
                color: var(--app-text) !important;
                border: 1px solid var(--app-border);
                border-radius: 22px;
            }

            .app-muted {
                color: var(--app-text-soft) !important;
            }

            .app-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                border-radius: 999px;
                padding: 0.45rem 0.85rem;
                background: rgba(148, 163, 184, 0.1);
                color: var(--app-text-soft);
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .app-button-primary {
                background: linear-gradient(135deg, #0f172a 0%, #0891b2 100%) !important;
                color: #fff !important;
                border: none !important;
                border-radius: 18px !important;
                box-shadow: 0 18px 40px -18px rgba(8, 145, 178, 0.9);
            }

            .app-button-danger {
                background: linear-gradient(135deg, #7f1d1d 0%, #e11d48 100%) !important;
                color: #fff !important;
                border: none !important;
                border-radius: 18px !important;
            }

            .theme-toggle-btn {
                background: var(--app-surface-strong) !important;
                color: var(--app-text) !important;
                border: 1px solid var(--app-border) !important;
            }

            .theme-toggle-btn .theme-pill {
                background: rgba(15, 23, 42, 0.06) !important;
            }

            html.dark .theme-toggle-btn .theme-pill {
                background: rgba(255, 255, 255, 0.06) !important;
            }

            .theme-switch {
                display: inline-flex;
                align-items: center;
                gap: 0.875rem;
                padding: 0.4rem 0.45rem 0.4rem 0.8rem;
                border-radius: 999px;
                background: var(--app-surface-strong);
                border: 1px solid var(--app-border);
                color: var(--app-text);
                box-shadow: 0 10px 30px -20px rgba(15, 23, 42, 0.45);
            }

            .theme-switch-track {
                position: relative;
                width: 54px;
                height: 30px;
                border-radius: 999px;
                background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
                transition: all 0.2s ease;
                flex-shrink: 0;
            }

            .theme-switch-thumb {
                position: absolute;
                top: 3px;
                left: 3px;
                width: 24px;
                height: 24px;
                border-radius: 999px;
                background: #ffffff;
                box-shadow: 0 8px 18px -12px rgba(15, 23, 42, 0.7);
                transition: transform 0.2s ease;
            }

            .theme-switch-icon {
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                transition: opacity 0.2s ease;
            }

            html.dark .theme-switch-track {
                background: linear-gradient(135deg, #0f172a 0%, #0891b2 100%);
            }

            html.dark .theme-switch-thumb {
                transform: translateX(24px);
                background: #e2e8f0;
            }

            .app-toast-stack {
                position: fixed;
                right: 1rem;
                bottom: 1rem;
                z-index: 120;
                display: flex;
                max-width: min(92vw, 380px);
                flex-direction: column;
                gap: 0.75rem;
            }

            .app-toast {
                border-radius: 20px;
                border: 1px solid var(--app-border);
                background: var(--app-surface-strong);
                color: var(--app-text);
                padding: 0.95rem 1rem;
                box-shadow: 0 24px 60px -35px rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(18px);
            }

            .app-toast.success { border-color: rgba(16, 185, 129, 0.28); }
            .app-toast.error { border-color: rgba(244, 63, 94, 0.28); }
            .app-toast.info { border-color: rgba(8, 145, 178, 0.28); }
        </style>

        <script>
            window.syncThemeUi = () => {
                const root = document.documentElement;
                const isDark = root.classList.contains('dark');

                document.querySelectorAll('[data-theme-label]').forEach((el) => {
                    el.textContent = isDark ? 'Dark' : 'Light';
                });

                document.querySelectorAll('[data-theme-mobile-label]').forEach((el) => {
                    el.textContent = isDark ? 'Aktifkan Light Mode' : 'Aktifkan Dark Mode';
                });

                document.querySelectorAll('[data-theme-icon-sun]').forEach((el) => {
                    el.style.display = isDark ? 'none' : 'block';
                });

                document.querySelectorAll('[data-theme-icon-moon]').forEach((el) => {
                    el.style.display = isDark ? 'block' : 'none';
                });

                document.querySelectorAll('[data-theme-switch-track]').forEach((el) => {
                    el.setAttribute('aria-checked', isDark ? 'true' : 'false');
                });
            };

            window.toggleTheme = () => {
                const root = document.documentElement;
                const nextDark = !root.classList.contains('dark');
                root.classList.toggle('dark', nextDark);
                root.style.colorScheme = nextDark ? 'dark' : 'light';
                localStorage.setItem('theme', nextDark ? 'dark' : 'light');
                window.syncThemeUi();
            };

            window.forceToggleTheme = () => {
                const root = document.documentElement;
                const nextDark = !root.classList.contains('dark');
                root.classList.toggle('dark', nextDark);
                root.setAttribute('data-theme', nextDark ? 'dark' : 'light');
                root.style.colorScheme = nextDark ? 'dark' : 'light';
                localStorage.setItem('theme', nextDark ? 'dark' : 'light');

                if (typeof window.syncThemeUi === 'function') {
                    window.syncThemeUi();
                }
            };

            document.addEventListener('DOMContentLoaded', () => {
                window.syncThemeUi();
            });

            const escapeToastHtml = (value) => String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

            window.appToast = (message, type = 'info') => {
                const stack = document.getElementById('app-toast-stack');

                if (!stack || !message) {
                    return;
                }

                const toast = document.createElement('div');
                toast.className = `app-toast ${type}`;
                toast.innerHTML = `
                    <div style="display:flex; align-items:flex-start; gap:.75rem;">
                        <div style="width:.6rem; height:.6rem; margin-top:.45rem; border-radius:999px; background:${type === 'error' ? '#f43f5e' : type === 'success' ? '#10b981' : '#0891b2'};"></div>
                        <div style="min-width:0;">
                            <p style="font-size:.875rem; font-weight:600;">${type === 'error' ? 'Terjadi masalah' : type === 'success' ? 'Berhasil' : 'Info'}</p>
                            <p style="margin-top:.25rem; font-size:.875rem; color:var(--app-text-soft);">${escapeToastHtml(message)}</p>
                        </div>
                    </div>
                `;

                stack.appendChild(toast);

                window.setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(8px)';
                    toast.style.transition = 'all .2s ease';
                    window.setTimeout(() => toast.remove(), 220);
                }, 2600);
            };
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <div class="app-shell min-h-screen bg-gray-100 transition-colors duration-300 dark:bg-slate-950">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="app-header bg-white/80 shadow-sm backdrop-blur-xl transition-colors duration-300 dark:bg-slate-900/80 dark:shadow-none">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <div id="app-toast-stack" class="app-toast-stack"></div>
    </body>
</html>
