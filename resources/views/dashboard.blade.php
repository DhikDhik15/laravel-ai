<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800">
            AI Chat
        </h2>
    </x-slot>

    <div class="fixed inset-0 top-[64px] overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.12),_transparent_32%),radial-gradient(circle_at_top_right,_rgba(168,85,247,0.10),_transparent_28%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)]">
        <div class="flex h-full w-full overflow-hidden">
            <!-- Sidebar -->
            <div class="hidden h-full w-72 flex-shrink-0 border-r border-white/60 bg-white/75 backdrop-blur-xl md:flex md:flex-col">
                <div class="border-b border-slate-200/80 p-5">
                    <div class="mb-4 rounded-3xl bg-slate-950 px-4 py-4 text-white shadow-[0_24px_60px_-28px_rgba(15,23,42,0.8)]">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-300">Workspace</p>
                        <p class="mt-2 text-lg font-semibold">Asisten AI</p>
                        <p class="mt-1 text-sm text-slate-400">Ruang kerja yang lebih rapi, ringan, dan nyaman dipakai.</p>
                    </div>
                    <button class="w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-slate-900/15 transition duration-200 hover:-translate-y-0.5 hover:bg-slate-800">
                        + Chat Baru
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Riwayat</p>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-500">1 aktif</span>
                    </div>
                    <div class="space-y-3">
                        <div class="cursor-pointer rounded-2xl border border-slate-200/80 bg-white/80 p-4 text-sm text-slate-700 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-white">
                            <p class="font-medium text-slate-800">Percakapan Baru</p>
                            <p class="mt-1 text-xs text-slate-500">Mulai sesi baru dengan prompt, gambar, audio, atau video.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Chat -->
            <div class="relative flex h-full flex-1 flex-col">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-white/50 to-transparent"></div>

                <!-- Chat Area -->
                <div id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-10">
                    <div class="mx-auto flex min-h-full w-full max-w-4xl flex-col justify-end space-y-4">
                        <div class="mb-6 rounded-[28px] border border-white/70 bg-white/75 p-5 text-slate-700 shadow-[0_25px_80px_-35px_rgba(15,23,42,0.35)] backdrop-blur-xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-600">AI Assistant</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">Mulai percakapan dengan tampilan yang lebih nyaman</h3>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                                Tulis pesan, lampirkan file, lalu lanjutkan obrolan seperti biasa. Semua fungsi tetap sama, hanya tampilannya dibuat lebih bersih dan modern.
                            </p>
                        </div>

                        <div class="flex justify-start">
                            <div class="max-w-[85%] rounded-[24px] border border-white/80 bg-white/90 px-5 py-4 text-slate-900 shadow-[0_18px_50px_-30px_rgba(15,23,42,0.45)] backdrop-blur">
                                Halo {{ Auth::user()->name }}, ada yang bisa saya bantu?
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="border-t border-white/70 bg-white/65 px-4 py-4 backdrop-blur-xl sm:px-6 lg:px-10">
                    <div class="mx-auto w-full max-w-4xl">
                        <form id="chat-form" class="rounded-[28px] border border-white/80 bg-white/85 p-3 shadow-[0_24px_80px_-30px_rgba(15,23,42,0.35)] backdrop-blur-xl" enctype="multipart/form-data">
                            @csrf

                            <div class="flex items-end gap-3">
                                <label for="file-input"
                                    class="flex h-12 w-12 flex-shrink-0 cursor-pointer items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-600 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 10-5.656-5.656L5.757 10.757a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </label>

                                <input
                                    type="file"
                                    id="file-input"
                                    class="hidden"
                                    accept="image/*,video/*,audio/*"
                                    multiple
                                >

                                <div class="flex-1 rounded-[22px] border border-slate-200 bg-white/90 p-2 shadow-inner shadow-slate-200/60 transition focus-within:border-sky-400 focus-within:ring-4 focus-within:ring-sky-100">
                                    <div id="preview-container" class="mb-2 hidden flex-wrap gap-2 px-2 pt-2"></div>

                                    <textarea
                                        id="message"
                                        rows="1"
                                        placeholder="Tulis pesan..."
                                        class="max-h-40 min-h-[44px] w-full resize-none border-none bg-transparent px-2 py-2 text-sm leading-6 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0"
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    id="send-button"
                                    class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-slate-900/15 transition duration-200 hover:-translate-y-0.5 hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
                                >
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let chatId = null;

        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const chatBox = document.getElementById('chat-box');
        const sendButton = document.getElementById('send-button');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const button = document.getElementById("send-button");

        let selectedFiles = [];

        function appendMessage(role, content, files = []) {
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;

            const filePreviewHtml = files.map(file => {
                if (file.type.startsWith('image/')) {
                    return `
                        <img src="${file.url}" alt="${file.name}" class="max-h-52 rounded-2xl border border-slate-200 object-cover shadow-sm" />
                    `;
                }

                if (file.type.startsWith('video/')) {
                    return `
                        <video controls class="max-h-52 rounded-2xl border border-slate-200 shadow-sm">
                            <source src="${file.url}" type="${file.type}">
                        </video>
                    `;
                }

                if (file.type.startsWith('audio/')) {
                    return `
                        <audio controls class="w-full">
                            <source src="${file.url}" type="${file.type}">
                        </audio>
                    `;
                }

                return `
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                        ${file.name}
                    </div>
                `;
            }).join('');

            wrapper.innerHTML = `
                <div class="max-w-[85%] rounded-[24px] px-5 py-4 text-sm shadow-[0_18px_50px_-30px_rgba(15,23,42,0.45)] ${
                    role === 'user'
                        ? 'bg-slate-950 text-white'
                        : 'border border-white/80 bg-white/90 text-slate-900 backdrop-blur'
                }">
                    ${filePreviewHtml ? `<div class="mb-2 space-y-2">${filePreviewHtml}</div>` : ''}
                    ${content ? `<p class="whitespace-pre-wrap break-words">${content}</p>` : ''}
                </div>
            `;

            const container = chatBox.querySelector('.space-y-4');
            container.appendChild(wrapper);

            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 50);
        }

        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = `${Math.min(this.scrollHeight, 160)}px`;
        });

        // Auto-focus saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', function() {
            messageInput.focus();
        });

        // Klik tekan tombol enter = menekan tombol kirim
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                button.click();
            }
        });

        fileInput.addEventListener('change', function(e) {
            selectedFiles = Array.from(e.target.files);
            previewContainer.innerHTML = '';

            if (selectedFiles.length === 0) {
                previewContainer.classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');

            selectedFiles.forEach(file => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative';

                if (file.type.startsWith('image/')) {
                    wrapper.innerHTML = `
                        <img src="${URL.createObjectURL(file)}" class="h-20 w-20 rounded-2xl border border-slate-200 object-cover shadow-sm" />
                    `;
                } else if (file.type.startsWith('video/')) {
                    wrapper.innerHTML = `
                        <video class="h-20 w-20 rounded-2xl border border-slate-200 object-cover shadow-sm">
                            <source src="${URL.createObjectURL(file)}" type="${file.type}">
                        </video>
                    `;
                } else if (file.type.startsWith('audio/')) {
                    wrapper.innerHTML = `
                        <div class="flex h-20 w-32 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-2 text-center text-xs font-medium text-slate-600 shadow-sm">
                            🎵 ${file.name}
                        </div>
                    `;
                }

                previewContainer.appendChild(wrapper);
            });
        });

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();

            if (!message && selectedFiles.length === 0) return;

            const previewFiles = selectedFiles.map(file => ({
                name: file.name,
                type: file.type,
                url: URL.createObjectURL(file),
            }));

            appendMessage('user', message, previewFiles);

            messageInput.value = '';
            messageInput.style.height = '44px';
            fileInput.value = '';
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            sendButton.disabled = true;
            sendButton.innerText = 'Mengirim...';

            try {
                const formData = new FormData();
                formData.append('chat_id', chatId ?? '');
                formData.append('message', message);

                selectedFiles.forEach(file => {
                    formData.append('files[]', file);
                });

                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: formData,
                });

                const data = await response.json();

                chatId = data.chat_id;

                appendMessage('assistant', data.reply);
            } catch (error) {
                appendMessage('assistant', 'Terjadi kesalahan saat menghubungi server.');
            } finally {
                selectedFiles = [];
                sendButton.disabled = false;
                sendButton.innerText = 'Kirim';
            }
        });
    </script>
</x-app-layout>
