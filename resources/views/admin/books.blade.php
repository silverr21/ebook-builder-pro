@extends('layouts.app')

@section('title', 'Správa kníh - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-600 hover:underline">← Admin</a>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">📚 Správa kníh</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Prehľad všetkých kníh v systéme</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-300">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6">
            <form method="GET" class="flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" placeholder="Hľadať knihu..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <select name="status" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    <option value="">Všetky statusy</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Koncept</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publikované</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivované</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">🔍 Filtrovať</button>
            </form>
        </div>

        <!-- Books Table -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50">
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Kniha</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Autor</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Vlastník</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Status</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Prečítania</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Vytvorené</th>
                            <th class="text-right px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Akcie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($books as $book)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">{{ $book->cover_image ? '📖' : '📕' }}</span>
                                    <span class="font-medium text-slate-900 dark:text-white line-clamp-1">{{ $book->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $book->author ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $book->user->name }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    @if($book->status === 'published') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                    @elseif($book->status === 'archived') bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400
                                    @else bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400
                                    @endif">
                                    @if($book->status === 'published') Publikované
                                    @elseif($book->status === 'archived') Archivované
                                    @else Koncept
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ number_format($book->view_count, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">{{ $book->created_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('editor.show', $book) }}" 
                                       class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                        ✏️ Editor
                                    </a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Zmazať knihu {{ $book->title }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                            🗑️ Zmazať
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
