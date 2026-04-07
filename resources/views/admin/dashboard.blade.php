<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">Admin AI Dashboard</h2>
                <p class="mt-1 text-sm app-muted">Statistik penggunaan chat dan aktivitas user.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="app-panel-soft rounded-full px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200">Kembali ke Chat</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="app-panel p-5"><p class="text-sm app-muted">Total User</p><p class="mt-3 text-3xl font-semibold">{{ $totalUsers }}</p></div>
            <div class="app-panel p-5"><p class="text-sm app-muted">Total Chat</p><p class="mt-3 text-3xl font-semibold">{{ $totalChats }}</p></div>
            <div class="app-panel p-5"><p class="text-sm app-muted">Total Pesan</p><p class="mt-3 text-3xl font-semibold">{{ $totalMessages }}</p></div>
            <div class="app-panel p-5"><p class="text-sm app-muted">Pesan User</p><p class="mt-3 text-3xl font-semibold">{{ $userMessages }}</p></div>
            <div class="app-panel p-5"><p class="text-sm app-muted">Jawaban AI</p><p class="mt-3 text-3xl font-semibold">{{ $assistantMessages }}</p></div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="app-panel p-6">
                <h3 class="text-lg font-semibold">Top User Berdasarkan Chat</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($topUsers as $user)
                        <div class="app-panel-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <div><p class="font-medium">{{ $user->name }}</p><p class="text-sm app-muted">{{ $user->email }}</p></div>
                            <span class="app-panel-soft rounded-full px-3 py-1 text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $user->chats_count }} chat</span>
                        </div>
                    @empty
                        <p class="text-sm app-muted">Belum ada data user.</p>
                    @endforelse
                </div>
            </section>
            <section class="app-panel p-6">
                <h3 class="text-lg font-semibold">Chat Terbaru</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($recentChats as $chat)
                        <div class="app-panel-soft rounded-2xl px-4 py-3">
                            <div class="flex items-start justify-between gap-4">
                                <div><p class="font-medium">{{ $chat->title ?: 'Chat tanpa judul' }}</p><p class="mt-1 text-sm app-muted">{{ $chat->user?->name ?? 'Unknown user' }}</p></div>
                                <span class="app-panel-soft rounded-full px-3 py-1 text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $chat->messages_count }} pesan</span>
                            </div>
                            <p class="mt-3 text-sm app-muted">{{ $chat->summary ?: 'Belum ada ringkasan percakapan.' }}</p>
                        </div>
                    @empty
                        <p class="text-sm app-muted">Belum ada chat terbaru.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
