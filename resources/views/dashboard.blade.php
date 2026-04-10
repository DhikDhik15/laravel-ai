<x-app-layout>
    <style>
        .dash-shell { height: calc(100vh - 128px); background: var(--app-bg); }
        .dash-panel, .dash-card, .dash-bubble-a, .dash-form { background: var(--app-surface-strong) !important; color: var(--app-text) !important; border: 1px solid var(--app-border); }
        .dash-muted { color: var(--app-text-soft) !important; }
        .dash-history.active { background: rgba(8,145,178,.10); border-color: rgba(8,145,178,.25); }
        .dash-history:hover { background: rgba(148,163,184,.08); }
        .dash-bubble-u { background: linear-gradient(135deg, #0f172a 0%, #0891b2 100%); color: #fff; }
        .dash-message-wrap { display:flex; width:100%; }
        .dash-message-wrap.user { justify-content:flex-end; }
        .dash-message-wrap.assistant { justify-content:flex-start; }
        .dash-message-card { max-width: 85%; }
        .dash-message-actions { display:flex; gap:.55rem; margin-top:.85rem; opacity:.95; }
        .dash-message-action { border:none; border-radius:999px; padding:.45rem .8rem; font-size:.72rem; font-weight:600; background:rgba(148,163,184,.12); color:var(--app-text-soft); transition:all .2s ease; }
        .dash-message-action:hover { background:rgba(8,145,178,.12); color:#0891b2; }
        .dash-history-group-title { margin: 1.1rem 0 .6rem; padding: 0 .35rem; font-size:.7rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--app-text-soft); }
        .dash-history-group-title:first-child { margin-top: 0; }
        .dash-markdown p + p { margin-top: .85rem; }
        .dash-markdown strong { font-weight: 700; color: var(--app-text); }
        .dash-markdown ul, .dash-markdown ol { margin: .75rem 0; padding-left: 1.25rem; }
        .dash-markdown li + li { margin-top: .35rem; }
        .dash-markdown h1, .dash-markdown h2, .dash-markdown h3 { margin-top: 1rem; font-weight: 700; line-height: 1.3; }
        .dash-markdown blockquote { margin-top: .85rem; border-left: 3px solid rgba(8,145,178,.4); padding-left: .85rem; color: var(--app-text-soft); }
        .dash-markdown a { color: #0891b2; text-decoration: underline; text-underline-offset: 3px; }
        .dash-markdown table { width: 100%; margin-top: .85rem; border-collapse: collapse; overflow: hidden; border-radius: 1rem; }
        .dash-markdown th, .dash-markdown td { border-bottom: 1px solid var(--app-border); padding: .65rem .8rem; text-align: left; }
        .dash-markdown th { background: rgba(148,163,184,.08); font-weight: 600; }
        .dash-markdown code:not(pre code) { border-radius: .5rem; background: rgba(148,163,184,.10); padding: .15rem .4rem; font-size: .92em; }
        .dash-markdown pre { overflow: auto; border-radius: 1rem; background: rgba(15,23,42,.92); padding: 1rem; color: #e2e8f0; margin-top: .85rem; }
        .dash-input-shell {
            border-radius: 28px;
            padding: 8px;
            background: linear-gradient(180deg, #eef4fb 0%, #e6edf7 100%) !important;
            border: 1px solid #d5e0ee !important;
        }
        html.dark .dash-input-shell {
            background: linear-gradient(180deg, #061536 0%, #081739 100%) !important;
            border: 1px solid rgba(62, 115, 193, .30) !important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.04), 0 14px 32px -24px rgba(2, 8, 24, .95);
        }
        .dash-input-bar {
            display: flex;
            align-items: center;
            gap: .7rem;
            border-radius: 999px;
            padding: .6rem .75rem;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            border: 1px solid #d8e0ea;
            box-shadow: 0 10px 30px -22px rgba(15, 23, 42, .35);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .dash-input-bar:focus-within {
            border-color: #d8e0ea;
            box-shadow: 0 10px 30px -22px rgba(15, 23, 42, .35);
        }
        html.dark .dash-input-bar {
            background: linear-gradient(90deg, #272a31 0%, #3a3c44 100%);
            border: 1px solid rgba(114, 149, 203, .22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.04);
        }
        html.dark .dash-input-bar:focus-within {
            border-color: rgba(77, 175, 216, .55);
            box-shadow: 0 0 0 2px rgba(77, 175, 216, .16), inset 0 1px 0 rgba(255,255,255,.05);
        }
        .dash-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
        }
        .dash-icon-btn.plus-btn { background: #e4eaf3; color: #334155; }
        .dash-icon-btn.plus-btn:hover { background: #d8e2ee; }
        html.dark .dash-icon-btn.plus-btn { background: rgba(255,255,255,.09); color: #f2f2f2; }
        html.dark .dash-icon-btn.plus-btn:hover { background: rgba(255,255,255,.16); }
        .dash-icon-btn.mic-btn { background: transparent; color: #526075; }
        .dash-icon-btn.mic-btn:hover { background: rgba(100,116,139,.14); color: #1e293b; }
        html.dark .dash-icon-btn.mic-btn { color: #f0f0f0; }
        html.dark .dash-icon-btn.mic-btn:hover { background: rgba(255,255,255,.10); color: #fff; }
        .dash-icon-btn.is-active { color: #0891b2 !important; background: rgba(8,145,178,.14); }
        .dash-send-orb {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: none;
            background: linear-gradient(135deg, #0e7490 0%, #0369a1 100%);
            color: #f8fafc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
            box-shadow: 0 10px 18px -12px rgba(3, 105, 161, .8);
        }
        .dash-send-orb:hover { transform: translateY(-1px) scale(1.03); }
        .dash-send-orb:disabled { opacity: .45; cursor: not-allowed; transform: none; }
        .dash-message-input {
            flex: 1;
            min-height: 34px;
            max-height: 120px;
            resize: none;
            border: none;
            background: transparent;
            color: #0f172a;
            padding: .35rem .2rem;
            font-size: .98rem;
            line-height: 1.45rem;
            caret-color: #0f172a;
        }
        html.dark .dash-message-input {
            color: #e7edf9;
            caret-color: #8fd6ff;
        }
        .dash-message-input::placeholder { color: #7c8ca3; }
        html.dark .dash-message-input::placeholder { color: #aab9d4; }
        .dash-message-input:focus { outline: none; }
        .dash-status { display:flex; align-items:center; gap:.55rem; border-radius:1rem; padding:.8rem 1rem; font-size:.85rem; }
        .dash-status.loading { background: rgba(8,145,178,.08); color:#0f766e; }
        .dash-status.error { background: rgba(244,63,94,.10); color:#be123c; }
        html.dark .dash-status.loading { color:#67e8f9; background: rgba(8,145,178,.12); }
        html.dark .dash-status.error { color:#fda4af; background: rgba(244,63,94,.14); }
        .dash-mobile-drawer { transition: transform .25s ease, opacity .25s ease; }
        .dash-mobile-drawer.hidden-drawer { transform: translateX(-110%); opacity: 0; pointer-events: none; }
        @media (max-width: 1023px) {
            .dash-shell { height: auto; min-height: calc(100vh - 128px); }
        }
        @media (min-width: 1024px) {
            .dash-mobile-drawer {
                transform: none !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            }
        }
        .typing span { display:inline-block; width:.42rem; height:.42rem; border-radius:999px; background:#94a3b8; margin-right:.2rem; animation:dots 1s infinite ease-in-out; }
        .typing span:nth-child(2){animation-delay:.15s}.typing span:nth-child(3){animation-delay:.3s;margin-right:0}
        @keyframes dots {0%,80%,100%{opacity:.35;transform:translateY(0)}40%{opacity:1;transform:translateY(-2px)}}
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">AI Workspace</h2>
                <p class="mt-1 text-sm dash-muted">Riwayat chat, streaming jawaban, dan dukungan dokumen dalam satu tempat.</p>
            </div>
            <button type="button" onclick="window.forceToggleTheme && window.forceToggleTheme()" data-theme-toggle class="theme-switch text-sm font-medium transition hover:opacity-90">
                <span data-theme-label>Light</span>
                <span data-theme-switch-track class="theme-switch-track" role="switch" aria-checked="false">
                    <span class="theme-switch-icon">
                        <svg data-theme-icon-sun xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 6a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707A1 1 0 003.343 5.757l.707.707zM4 11a1 1 0 100-2H3a1 1 0 000 2h1zm1.757 4.657a1 1 0 00-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707z" /></svg>
                        <svg data-theme-icon-moon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                    </span>
                    <span class="theme-switch-thumb"></span>
                </span>
            </button>
        </div>
    </x-slot>

    <div class="dash-shell px-4 py-4 sm:px-6 lg:px-8" data-dashboard data-chats='@json($chats)' data-chat='@json($selectedChat)' data-chat-id='@json($selectedChatId)' data-admin='@json($adminStatsUrl)'>
        <div class="mb-4 flex items-center justify-between gap-3 lg:hidden">
            <button type="button" id="open-history-button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                Buka Riwayat
            </button>
            <div class="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                {{ $chats->count() }} chat
            </div>
        </div>
        <div class="grid h-full min-h-0 gap-4 lg:grid-cols-[320px_minmax(0,1fr)]">
            <div id="history-backdrop" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden"></div>
            <aside id="history-sidebar" class="dash-mobile-drawer hidden-drawer dash-panel fixed inset-y-0 left-4 top-[96px] z-40 w-[min(88vw,320px)] flex min-h-0 flex-col rounded-[28px] p-4 shadow-[0_20px_60px_-40px_rgba(15,23,42,.35)] lg:static lg:inset-auto lg:left-auto lg:top-auto lg:z-auto lg:w-auto lg:translate-x-0 lg:opacity-100 lg:pointer-events-auto">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] dash-muted">Workspace</p>
                        <h3 class="mt-2 text-lg font-semibold">Percakapan</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($adminStatsUrl)
                            <a href="{{ $adminStatsUrl }}" class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">Admin</a>
                        @endif
                        <button type="button" id="close-history-button" class="rounded-full bg-slate-100 p-2 text-slate-600 dark:bg-slate-800 dark:text-slate-300 lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <button type="button" id="new-chat-button" class="mt-4 rounded-2xl px-4 py-3 text-left text-sm font-semibold text-white shadow-[0_18px_40px_-18px_rgba(8,145,178,.85)] transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, #0f172a 0%, #0891b2 100%); color:#fff; border:none;">
                    + Chat Baru
                </button>
                <div class="mt-4">
                    <input id="history-search" type="text" placeholder="Cari chat..." class="w-full rounded-2xl border-none bg-slate-100 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-cyan-900">
                </div>
                <div class="mt-5 flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] dash-muted">Riwayat</p>
                    <span id="history-count" class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $chats->count() }}</span>
                </div>
                <div id="history-list" class="mt-4 flex-1 space-y-2 overflow-y-auto pr-1"></div>
            </aside>

            <section class="flex min-h-0 flex-col">
                <div id="chat-box" class="min-h-0 flex-1 overflow-y-auto">
                    <div class="space-y-5">
                        <div class="dash-panel rounded-[28px] p-6 shadow-[0_20px_60px_-40px_rgba(15,23,42,.35)]">
                            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-600 dark:text-cyan-400">AI Assistant</p>
                                    <h3 id="hero-title" class="mt-2 text-3xl font-semibold tracking-tight">Ngobrol lebih cepat, rapi, dan kontekstual</h3>
                                    <p id="hero-summary" class="mt-3 max-w-2xl text-sm leading-7 dash-muted">Pilih percakapan di sidebar, kirim prompt baru, unggah dokumen teks, dan baca respons AI dengan tampilan yang lebih nyaman.</p>
                                </div>
                                <div class="dash-card rounded-2xl p-4 text-sm shadow-sm">
                                    <p class="font-semibold">Fitur Aktif</p>
                                    <ul class="mt-3 space-y-2 dash-muted">
                                        <li>Riwayat chat sungguhan</li>
                                        <li>Rename dan hapus chat</li>
                                        <li>Streaming jawaban bertahap</li>
                                        <li>Dukungan dokumen teks</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="stream-status"></div>
                        <div id="messages-container" class="space-y-5"></div>
                    </div>
                </div>

                <div class="pt-4">
                    <form id="chat-form" class="dash-form dash-input-shell shadow-[0_20px_60px_-40px_rgba(15,23,42,.35)]" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-3">
                            <div id="preview-container" class="hidden flex-wrap gap-2 px-1 pt-1"></div>
                            <div class="dash-input-bar">
                                <label for="file-input" class="dash-icon-btn plus-btn cursor-pointer" title="Unggah File">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.15" d="M12 5.25v13.5M5.25 12h13.5" />
                                    </svg>
                                </label>
                                <input type="file" id="file-input" class="hidden" accept="image/*,video/*,audio/*,.txt,.md,.csv,.json,.xml,.log" multiple>
                                <textarea id="message" rows="1" placeholder="Ask anything" class="dash-message-input"></textarea>
                                <button type="button" id="dictate-button" class="dash-icon-btn mic-btn" title="Dikte (Voice-to-Text)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3.75a3.25 3.25 0 00-3.25 3.25v3.75a3.25 3.25 0 106.5 0V7A3.25 3.25 0 0012 3.75z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 10.5a5.25 5.25 0 1010.5 0M12 15.75v3m-3 0h6" />
                                    </svg>
                                </button>
                                <button type="submit" id="send-button" class="dash-send-orb" title="Kirim">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M3.83 20.24a1 1 0 001.39 1.16l15.5-8.37a1.15 1.15 0 000-2.06L5.22 2.6a1 1 0 00-1.39 1.16l2.03 6.79a1 1 0 00.95.71h6.24a.75.75 0 010 1.5H6.81a1 1 0 00-.95.71l-2.03 6.78z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        const dashboardEl = document.querySelector('[data-dashboard]');
        const chatsData = JSON.parse(dashboardEl.dataset.chats || '[]');
        const selectedData = JSON.parse(dashboardEl.dataset.chat || 'null');
        const selectedId = JSON.parse(dashboardEl.dataset.chatId || 'null');
        const csrf = document.querySelector('input[name="_token"]').value;

        const historyList = document.getElementById('history-list');
        const historyCount = document.getElementById('history-count');
        const messagesContainer = document.getElementById('messages-container');
        const heroTitle = document.getElementById('hero-title');
        const heroSummary = document.getElementById('hero-summary');
        const newChatButton = document.getElementById('new-chat-button');
        const historySearch = document.getElementById('history-search');
        const openHistoryButton = document.getElementById('open-history-button');
        const closeHistoryButton = document.getElementById('close-history-button');
        const historySidebar = document.getElementById('history-sidebar');
        const historyBackdrop = document.getElementById('history-backdrop');
        const streamStatus = document.getElementById('stream-status');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const sendButton = document.getElementById('send-button');
        const chatBox = document.getElementById('chat-box');

        let chats = Array.isArray(chatsData) ? chatsData : [];
        let currentChat = selectedData;
        let chatId = selectedId;
        let selectedFiles = [];
        let historyQuery = '';

        const esc = (v) => String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
        const fmt = (v) => v ? new Intl.DateTimeFormat('id-ID',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'}).format(new Date(v)) : 'Baru';

        function markdownToHtml(text = '') {
            const normalized = String(text ?? '').replace(/\r\n/g, '\n').trim();

            if (!normalized) {
                return '<p></p>';
            }

            const applyInline = (value) => esc(value)
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/__(.+?)__/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/`([^`]+)`/g, '<code>$1</code>');

            return normalized
                .split(/\n{2,}/)
                .map((block) => block.trim())
                .filter(Boolean)
                .map((block) => {
                    const lines = block.split('\n').map((line) => line.trim()).filter(Boolean);

                    if (lines.every((line) => /^[-*]\s+/.test(line))) {
                        return `<ul>${lines.map((line) => `<li>${applyInline(line.replace(/^[-*]\s+/, ''))}</li>`).join('')}</ul>`;
                    }

                    if (lines.every((line) => /^\d+\.\s+/.test(line))) {
                        return `<ol>${lines.map((line) => `<li>${applyInline(line.replace(/^\d+\.\s+/, ''))}</li>`).join('')}</ol>`;
                    }

                    return `<p>${lines.map((line) => applyInline(line)).join('<br>')}</p>`;
                })
                .join('');
        }

        function setStatus(type = '', message = '') {
            if (!message) {
                streamStatus.innerHTML = '';
                return;
            }

            streamStatus.innerHTML = `
                <div class="dash-status ${type}">
                    <span class="${type === 'loading' ? 'typing' : ''}">
                        ${type === 'loading' ? '<span></span><span></span><span></span>' : ''}
                    </span>
                    <span>${esc(message)}</span>
                </div>
            `;
        }

        function openSidebar() {
            historySidebar.classList.remove('hidden-drawer');
            historyBackdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            historySidebar.classList.add('hidden-drawer');
            historyBackdrop.classList.add('hidden');
        }

        function attachmentCard(file) {
            const mime = (file.mime || file.type || '');
            if (mime.startsWith('image/')) {
                return `<a href="${file.url}" target="_blank" title="${esc(file.name)}"><img src="${file.url}" alt="${esc(file.name)}" class="h-20 w-20 rounded-xl object-cover shadow-sm border border-slate-200 dark:border-slate-700"></a>`;
            }
            if (mime.startsWith('video/')) {
                return `<video src="${file.url}" controls class="h-28 w-auto rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 max-w-full" title="${esc(file.name)}"></video>`;
            }
            if (mime.startsWith('audio/')) {
                return `<audio src="${file.url}" controls class="h-10 w-56 max-w-full rounded-full shadow-sm border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800" title="${esc(file.name)}"></audio>`;
            }
            return `<a href="${file.url}" target="_blank" class="rounded-2xl bg-white px-3 py-2 text-xs text-slate-700 dash-muted border border-slate-200 hover:border-slate-300 shadow-sm dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700 transition hover:bg-slate-50 dark:hover:bg-slate-750 flex items-center gap-2 max-w-xs overflow-hidden">
                <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                <div class="min-w-0 flex-1 truncate">
                    <div class="font-semibold text-slate-900 dark:text-slate-100 truncate">${esc(file.name)}</div>
                    <div class="mt-0.5 text-[10px] truncate">${esc(mime || 'Lampiran')} ${file.size ? '• ' + Math.round(file.size/1024) + ' KB' : ''}</div>
                </div>
            </a>`;
        }

        function renderAttachments(files = []) {
            if (!files.length) return '';
            return `<div class="mb-3 flex flex-wrap gap-2">${files.map((f) => {
                const item = typeof f === 'string' ? {name:'Gambar', type:'image/jpeg', url:`data:image/jpeg;base64,${f}`} : f;
                return attachmentCard(item);
            }).join('')}</div>`;
        }

        function setHero(chat) {
            heroTitle.textContent = chat?.title || 'Ngobrol lebih cepat, rapi, dan kontekstual';
            heroSummary.textContent = chat?.summary || 'Pilih percakapan di sidebar, kirim prompt baru, unggah dokumen teks, dan baca respons AI dengan tampilan yang lebih nyaman.';
        }

        function summarizeChat(chat) {
            if (chat?.summary) {
                return chat.summary;
            }

            const messages = Array.isArray(chat?.messages) ? chat.messages : [];
            const lastMessage = [...messages].reverse().find((message) => {
                return (message?.content && String(message.content).trim() !== '') || (Array.isArray(message?.files) && message.files.length);
            });

            if (lastMessage?.content && String(lastMessage.content).trim() !== '') {
                return String(lastMessage.content).trim().slice(0, 140);
            }

            if (Array.isArray(lastMessage?.files) && lastMessage.files.length) {
                return 'Chat terakhir berisi lampiran file.';
            }

            return 'Chat baru dimulai.';
        }

        function historyGroupLabel(value) {
            if (!value) return 'Lebih Lama';

            const date = new Date(value);
            const now = new Date();
            const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const startOfTarget = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const diffDays = Math.round((startOfToday - startOfTarget) / 86400000);

            if (diffDays <= 0) return 'Hari Ini';
            if (diffDays === 1) return 'Kemarin';
            if (diffDays <= 7) return '7 Hari Terakhir';
            if (date.getFullYear() === now.getFullYear() && date.getMonth() === now.getMonth()) return 'Bulan Ini';
            return 'Lebih Lama';
        }

        function renderHistory() {
            const filteredChats = chats.filter((chat) => {
                const haystack = `${chat.title || ''} ${chat.summary || ''}`.toLowerCase();
                return haystack.includes(historyQuery.toLowerCase());
            });

            historyCount.textContent = filteredChats.length;
            if (!filteredChats.length) {
                historyList.innerHTML = `<div class="dash-card rounded-2xl p-4 text-sm dash-muted">${historyQuery ? 'Tidak ada chat yang cocok dengan pencarian.' : 'Belum ada chat. Mulai dari tombol "Chat Baru".'}</div>`;
                return;
            }

            const grouped = filteredChats.reduce((acc, chat) => {
                const label = historyGroupLabel(chat.last_message_at || chat.created_at);
                acc[label] = acc[label] || [];
                acc[label].push(chat);
                return acc;
            }, {});

            const order = ['Hari Ini', 'Kemarin', '7 Hari Terakhir', 'Bulan Ini', 'Lebih Lama'];

            historyList.innerHTML = order
                .filter((label) => grouped[label]?.length)
                .map((label) => `
                    <div>
                        <p class="dash-history-group-title">${label}</p>
                        <div class="space-y-2">
                            ${grouped[label].map((chat) => `
                                <div class="dash-history ${chat.id === chatId ? 'active' : ''} rounded-2xl border p-3" data-chat="${chat.id}">
                                    <button type="button" class="w-full text-left" onclick="selectChat(${chat.id})">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold">${esc(chat.title || 'Chat tanpa judul')}</p>
                                                <p class="mt-1 line-clamp-2 text-xs dash-muted">${esc(summarizeChat(chat))}</p>
                                            </div>
                                            <span class="shrink-0 text-[11px] dash-muted">${fmt(chat.last_message_at || chat.created_at)}</span>
                                        </div>
                                    </button>
                                    <div class="mt-3 flex gap-2">
                                        <button type="button" class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300" onclick="renameChat(${chat.id})">Rename</button>
                                        <button type="button" class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-600 dark:bg-rose-950/40 dark:text-rose-300" onclick="deleteChat(${chat.id})">Hapus</button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `)
                .join('');
        }

        function renderMessages(messages = []) {
            if (!messages.length) {
                messagesContainer.innerHTML = `<div class="dash-panel rounded-[28px] p-6 text-center shadow-[0_20px_60px_-40px_rgba(15,23,42,.35)]"><p class="text-lg font-semibold">Belum ada pesan</p><p class="mt-2 dash-muted">Kirim pesan pertama untuk mulai percakapan.</p></div>`;
                return;
            }
            messagesContainer.innerHTML = messages.map((msg, index) => {
                const user = msg.role === 'user';
                const bubble = user ? 'dash-bubble-u' : 'dash-bubble-a dash-markdown';
                const content = user
                    ? `<p class="whitespace-pre-wrap break-words leading-7">${esc(msg.content || '')}</p>`
                    : `<div class="whitespace-pre-wrap break-words leading-7">${msg.html || markdownToHtml(msg.content || '')}</div>`;

                const actions = user ? '' : `
                    <div class="dash-message-actions">
                        <button type="button" class="dash-message-action" data-copy-message="${index}">Copy</button>
                        <button type="button" class="dash-message-action" data-regenerate-message="${index}">Regenerate</button>
                    </div>
                `;

                return `
                    <div class="dash-message-wrap ${user ? 'user' : 'assistant'}">
                        <div class="dash-message-card">
                            <div class="rounded-[24px] px-5 py-4 text-sm shadow-[0_20px_50px_-35px_rgba(15,23,42,.32)] ${bubble}">
                                ${renderAttachments(msg.files || [])}
                                ${content}
                            </div>
                            ${actions}
                        </div>
                    </div>
                `;
            }).join('');
            requestAnimationFrame(() => { chatBox.scrollTop = chatBox.scrollHeight; });
        }

        async function loadChat(id) {
            const res = await fetch(`/chats/${id}`, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('Gagal memuat chat.');
            return res.json();
        }

        window.selectChat = async (id) => {
            try {
                const data = await loadChat(id);
                currentChat = data.chat;
                chatId = id;
                setHero(currentChat);
                renderHistory();
                renderMessages((currentChat.messages || []).map((m) => ({ ...m, html: m.role === 'assistant' ? markdownToHtml(m.content || '') : undefined })));
                closeSidebar();
            } catch (e) { alert(e.message); }
        };

        window.renameChat = async (id) => {
            const item = chats.find((c) => c.id === id);
            const title = prompt('Masukkan judul baru:', item?.title || '');
            if (!title) return;
            const res = await fetch(`/chats/${id}`, { method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,Accept:'application/json'}, body: JSON.stringify({ title }) });
            if (!res.ok) return window.appToast?.('Gagal rename chat.', 'error');
            const data = await res.json();
            chats = chats.map((c) => c.id === id ? { ...c, ...data.chat } : c);
            if (currentChat?.id === id) { currentChat = { ...currentChat, ...data.chat }; setHero(currentChat); }
            renderHistory();
            window.appToast?.('Judul chat berhasil diperbarui.', 'success');
        };

        window.deleteChat = async (id) => {
            if (!confirm('Hapus percakapan ini?')) return;
            const res = await fetch(`/chats/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,Accept:'application/json'} });
            if (!res.ok) return window.appToast?.('Gagal menghapus chat.', 'error');
            chats = chats.filter((c) => c.id !== id);
            if (chatId === id) { chatId = null; currentChat = null; setHero(null); renderMessages([]); }
            renderHistory();
            window.appToast?.('Percakapan dihapus.', 'success');
        };

        function newChat() { chatId = null; currentChat = null; setHero(null); renderHistory(); renderMessages([]); setStatus(); closeSidebar(); messageInput.focus(); }

        function pushMessage(msg) {
            const msgs = currentChat?.messages ? [...currentChat.messages] : [];
            msgs.push(msg);
            currentChat = { ...(currentChat || {}), id: chatId, title: currentChat?.title, summary: currentChat?.summary, messages: msgs };
            renderMessages(msgs);
        }

        async function submitChatMessage(message, files = []) {
            const fd = new FormData();
            fd.append('chat_id', chatId ?? '');
            fd.append('message', message || '');
            files.forEach((file) => fd.append('files[]', file));

            const res = await fetch('/chat/send', { method:'POST', headers:{'X-CSRF-TOKEN':csrf,Accept:'application/json'}, body:fd });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Gagal mengirim pesan.');

            chatId = data.chat_id;
            const meta = { id:data.chat.id, title:data.chat.title, summary:data.chat.summary || message || 'Percakapan aktif', last_message_at:data.chat.last_message_at };
            const idx = chats.findIndex((c) => c.id === meta.id);
            if (idx >= 0) chats[idx] = { ...chats[idx], ...meta };
            else chats.unshift(meta);
            currentChat = { ...(currentChat || {}), ...meta, messages: currentChat?.messages || [] };
            renderHistory();
            setHero(currentChat);
            await streamReply(data.stream_url);
        }

        async function streamReply(streamUrl) {
            const msgs = currentChat?.messages ? [...currentChat.messages] : [];
            const draft = { role:'assistant', content:'', html:'<div class="typing"><span></span><span></span><span></span></div>', files:[] };
            msgs.push(draft);
            currentChat = { ...(currentChat || {}), messages: msgs };
            renderMessages(msgs);
            setStatus('loading', 'AI sedang menyiapkan jawaban...');
            await new Promise((resolve, reject) => {
                const source = new EventSource(streamUrl);

                source.addEventListener('delta', (event) => {
                    const payload = JSON.parse(event.data);
                    draft.content += payload.delta || '';
                    draft.html = markdownToHtml(draft.content);
                    renderMessages(msgs);
                    setStatus('loading', 'Sedang menerima jawaban AI secara real-time...');
                });

                source.addEventListener('status', (event) => {
                    try {
                        const payload = JSON.parse(event.data);
                        setStatus('loading', payload.message || 'Mencoba menghubungkan ulang ke layanan AI...');
                    } catch (_) {
                        setStatus('loading', 'Mencoba menghubungkan ulang ke layanan AI...');
                    }
                });

                source.addEventListener('done', (event) => {
                    const payload = JSON.parse(event.data);
                    draft.content = payload.assistant_message.content;
                    draft.html = markdownToHtml(payload.assistant_message.content || '');
                    currentChat = {
                        ...(currentChat || {}),
                        ...payload.chat,
                        messages: msgs,
                    };

                    const idx = chats.findIndex((c) => c.id === payload.chat.id);
                    if (idx >= 0) {
                        chats[idx] = { ...chats[idx], ...payload.chat };
                    }

                    setHero(currentChat);
                    renderHistory();
                    renderMessages(msgs);
                    setStatus();
                    source.close();
                    resolve();
                });

                source.addEventListener('error', (event) => {
                    source.close();

                    try {
                        if (event?.data) {
                            const payload = JSON.parse(event.data);
                            setStatus('error', payload.message || 'Streaming gagal.');
                            reject(new Error(payload.message || 'Streaming gagal.'));
                            return;
                        }
                    } catch (_) {
                    }

                    setStatus('error', 'Streaming gagal.');
                    reject(new Error('Streaming gagal.'));
                });
            });
        }

        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = `${Math.min(this.scrollHeight, 160)}px`;
        });

        messageInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.requestSubmit();
            }
        });

        function renderPreview() {
            previewContainer.innerHTML = '';
            if (!selectedFiles.length) return previewContainer.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewContainer.innerHTML = selectedFiles.map((file, i) => {
                const url = URL.createObjectURL(file);
                let media = `<div class="rounded-2xl bg-white shadow-sm px-3 py-2 border border-slate-200 flex items-center text-xs dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-200"><svg class="w-3.5 h-3.5 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg><span class="truncate max-w-[120px]">${esc(file.name)}</span></div>`;
                if (file.type.startsWith('image/')) media = `<img src="${url}" class="h-14 w-14 rounded-2xl object-cover shadow-md border border-slate-200 dark:border-slate-700">`;
                if (file.type.startsWith('video/')) media = `<video src="${url}" class="h-14 w-14 rounded-2xl object-cover shadow-md border border-slate-200 dark:border-slate-700"></video>`;
                if (file.type.startsWith('audio/')) media = `<div class="rounded-2xl bg-white shadow-sm px-3 py-2 border border-slate-200 flex items-center text-xs dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-200"><svg class="w-4 h-4 mr-1.5 text-indigo-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg><span class="truncate max-w-[120px]">${esc(file.name)}</span></div>`;

                return `<div class="relative group cursor-pointer" onclick="selectedFiles.splice(${i}, 1); renderPreview();" title="Klik untuk menghapus">
                    ${media}
                    <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </div>`;
            }).join('');
        }

        fileInput.addEventListener('change', function (e) {
            selectedFiles = [...selectedFiles, ...Array.from(e.target.files)];
            renderPreview();
            fileInput.value = '';
        });

        const recordButton = document.getElementById('record-button');
        let mediaRecorder = null;
        let audioChunks = [];
        let isRecording = false;

        if (recordButton) {
            recordButton.addEventListener('click', async () => {
                if (isRecording) {
                    mediaRecorder.stop();
                    return;
                }

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    const mimeTypes = ['audio/webm;codecs=opus', 'audio/webm', 'audio/ogg;codecs=opus', 'audio/ogg', 'audio/wav'];
                    const supportedMime = mimeTypes.find(type => MediaRecorder.isTypeSupported(type)) || 'audio/webm';
                    
                    mediaRecorder = new MediaRecorder(stream, { mimeType: supportedMime });

                    mediaRecorder.addEventListener('dataavailable', event => {
                        if (event.data.size > 0) audioChunks.push(event.data);
                    });

                    mediaRecorder.addEventListener('stop', () => {
                        stream.getTracks().forEach(track => track.stop());
                        const audioBlob = new Blob(audioChunks, { type: supportedMime });
                        const extension = supportedMime.includes('ogg') ? '.ogg' : '.webm';
                        const audioFile = new File([audioBlob], `Voice_Note_${new Date().getTime()}${extension}`, { type: supportedMime });
                        selectedFiles.push(audioFile);
                        renderPreview();
                        
                        isRecording = false;
                    });

                    audioChunks = [];
                    mediaRecorder.start();
                    isRecording = true;
                } catch (e) {
                    window.appToast?.('Gagal mengakses mikropon. Pastikan izin telah diberikan.', 'error');
                }
            });
        }

        const dictateButton = document.getElementById('dictate-button');
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let recognition = null;
        let isDictating = false;

        if (SpeechRecognition) {
            recognition = new SpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = 'id-ID';

            recognition.onresult = (event) => {
                let interimTranscript = '';
                let finalTranscript = '';

                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    } else {
                        interimTranscript += event.results[i][0].transcript;
                    }
                }

                if (finalTranscript) {
                    const currentPos = messageInput.selectionStart;
                    const textBefore = messageInput.value.substring(0, currentPos);
                    const textAfter = messageInput.value.substring(currentPos);
                    messageInput.value = textBefore + finalTranscript + ' ' + textAfter;
                    messageInput.dispatchEvent(new Event('input'));
                }
            };

            recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
                stopDictation();
                if (event.error === 'not-allowed') {
                    window.appToast?.('Izin mikropon ditolak.', 'error');
                }
            };

            recognition.onend = () => {
                if (isDictating) recognition.start();
            };

            function stopDictation() {
                isDictating = false;
                recognition.stop();
                dictateButton.classList.remove('animate-pulse', 'is-active');
            }

            dictateButton.addEventListener('click', () => {
                if (isDictating) {
                    stopDictation();
                } else {
                    isDictating = true;
                    recognition.start();
                    dictateButton.classList.add('animate-pulse', 'is-active');
                }
            });
        } else {
            dictateButton.classList.add('hidden');
        }

        messagesContainer.addEventListener('click', async (event) => {
            const copyButton = event.target.closest('[data-copy-message]');
            if (copyButton) {
                const index = Number(copyButton.dataset.copyMessage);
                const msg = currentChat?.messages?.[index];
                if (!msg?.content) return;

                try {
                    await navigator.clipboard.writeText(msg.content);
                    window.appToast?.('Jawaban AI berhasil disalin.', 'success');
                } catch (_) {
                    window.appToast?.('Clipboard tidak tersedia di browser ini.', 'error');
                }
                return;
            }

            const regenerateButton = event.target.closest('[data-regenerate-message]');
            if (regenerateButton) {
                const index = Number(regenerateButton.dataset.regenerateMessage);
                const priorMessages = currentChat?.messages?.slice(0, index) || [];
                const lastUser = [...priorMessages].reverse().find((message) => message.role === 'user');

                if (!lastUser?.content || (lastUser.files && lastUser.files.length)) {
                    window.appToast?.('Regenerate saat ini hanya mendukung prompt teks tanpa lampiran.', 'info');
                    return;
                }

                pushMessage({ role:'user', content:lastUser.content, files:[] });
                setStatus('loading', 'Mengirim ulang prompt terakhir...');

                try {
                    await submitChatMessage(lastUser.content, []);
                    window.appToast?.('Prompt berhasil dikirim ulang.', 'success');
                } catch (error) {
                    setStatus('error', error.message || 'Gagal mengirim ulang prompt.');
                    window.appToast?.(error.message || 'Gagal mengirim ulang prompt.', 'error');
                }
            }
        });

        chatForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message && selectedFiles.length === 0) return;

            const localFiles = selectedFiles.map((file) => ({ name:file.name, mime:file.type, type:file.type, url:URL.createObjectURL(file) }));
            pushMessage({ role:'user', content:message, files:localFiles });

            messageInput.value = '';
            messageInput.style.height = '48px';
            fileInput.value = '';
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            sendButton.disabled = true;
            sendButton.classList.add('opacity-60');

            try {
                await submitChatMessage(message, selectedFiles);
            } catch (error) {
                setStatus('error', error.message || 'Terjadi kesalahan saat menghubungi server.');
                pushMessage({ role:'assistant', content:error.message || 'Terjadi kesalahan saat menghubungi server.', html: markdownToHtml(error.message || 'Terjadi kesalahan saat menghubungi server.'), files:[] });
                window.appToast?.(error.message || 'Terjadi kesalahan saat menghubungi server.', 'error');
            } finally {
                selectedFiles = [];
                sendButton.disabled = false;
                sendButton.classList.remove('opacity-60');
            }
        });

        newChatButton.addEventListener('click', newChat);
        historySearch.addEventListener('input', function () {
            historyQuery = this.value || '';
            renderHistory();
        });
        openHistoryButton?.addEventListener('click', openSidebar);
        closeHistoryButton?.addEventListener('click', closeSidebar);
        historyBackdrop?.addEventListener('click', closeSidebar);
        setHero(currentChat);
        renderHistory();
        renderMessages((selectedData?.messages || []).map((m) => ({ ...m, html: m.role === 'assistant' ? markdownToHtml(m.content || '') : undefined })));
    </script>
</x-app-layout>
