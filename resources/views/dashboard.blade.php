<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            AI Chat
        </h2>
    </x-slot>

    <div class="fixed inset-0 top-[64px] bg-gray-100">
        <div class="flex h-full w-full overflow-hidden">
            <!-- Sidebar -->
            <div class="hidden h-full w-72 flex-shrink-0 border-r border-gray-200 bg-white md:flex md:flex-col">
                <div class="border-b p-4">
                    <button class="w-full rounded-xl bg-black px-4 py-3 text-sm font-medium text-white transition hover:bg-gray-800">
                        + Chat Baru
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <div class="space-y-2">
                        <div class="cursor-pointer rounded-xl bg-gray-100 p-3 text-sm text-gray-700 transition hover:bg-gray-200">
                            Percakapan Baru
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Chat -->
            <div class="flex h-full flex-1 flex-col bg-gray-50">
                <!-- Chat Area -->
                <div id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-10">
                    <div class="mx-auto flex min-h-full w-full max-w-4xl flex-col justify-end space-y-4">
                        <div class="flex justify-start">
                            <div class="max-w-[85%] rounded-2xl bg-white px-4 py-3 text-gray-900 shadow-sm">
                                Halo {{ Auth::user()->name }}, ada yang bisa saya bantu?
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="border-t border-gray-200 bg-white px-4 py-4 sm:px-6 lg:px-10">
                    <div class="mx-auto w-full max-w-4xl">
                        <form id="chat-form" class="flex items-end gap-3" enctype="multipart/form-data">
                            @csrf

                            <label for="file-input"
                                class="flex h-12 w-12 cursor-pointer items-center justify-center rounded-2xl border border-gray-300 bg-white text-gray-600 shadow-sm transition hover:bg-gray-100 hover:text-black">
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

                            <div class="flex-1 rounded-2xl border border-gray-300 bg-white p-2 shadow-sm focus-within:border-black">
                                <div id="preview-container" class="mb-2 hidden flex-wrap gap-2 px-2 pt-2"></div>

                                <textarea
                                    id="message"
                                    rows="1"
                                    placeholder="Tulis pesan..."
                                    class="max-h-40 min-h-[44px] w-full resize-none border-none bg-transparent px-2 py-2 text-sm focus:outline-none focus:ring-0"
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                id="send-button"
                                class="rounded-2xl bg-black px-5 py-3 text-sm font-medium text-white"
                            >
                                Kirim
                            </button>
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
                        <img src="${file.url}" alt="${file.name}" class="max-h-52 rounded-xl border object-cover" />
                    `;
                }

                if (file.type.startsWith('video/')) {
                    return `
                        <video controls class="max-h-52 rounded-xl border">
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
                    <div class="rounded-lg border bg-gray-100 px-3 py-2 text-xs text-gray-700">
                        ${file.name}
                    </div>
                `;
            }).join('');

            wrapper.innerHTML = `
                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm ${
                    role === 'user'
                        ? 'bg-black text-white'
                        : 'bg-white text-gray-900'
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
                        <img src="${URL.createObjectURL(file)}" class="h-20 w-20 rounded-lg border object-cover" />
                    `;
                } else if (file.type.startsWith('video/')) {
                    wrapper.innerHTML = `
                        <video class="h-20 w-20 rounded-lg border object-cover">
                            <source src="${URL.createObjectURL(file)}" type="${file.type}">
                        </video>
                    `;
                } else if (file.type.startsWith('audio/')) {
                    wrapper.innerHTML = `
                        <div class="flex h-20 w-32 items-center justify-center rounded-lg border bg-gray-100 px-2 text-center text-xs text-gray-600">
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