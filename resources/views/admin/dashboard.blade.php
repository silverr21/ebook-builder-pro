@extends('layouts.app')

@section('title', 'Admin Dashboard - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium rounded-full">
                    🔒 Admin Panel
                </div>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">🛡️ Admin Dashboard</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Správa systému, používateľov a kníh</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-300">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Quick Navigation -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.users') }}" class="group bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition hover:shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        👥
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">Správa používateľov</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $stats['total_users'] }} používateľov</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.books') }}" class="group bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-purple-400 transition hover:shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        📚
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">Správa kníh</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $stats['total_books'] }} kníh</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.settings') }}" class="group bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-green-400 transition hover:shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        ⚙️
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">Nastavenia</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Systémová konfigurácia</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <div class="text-2xl mb-1">👥</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_users'] }}</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Používatelia</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <div class="text-2xl mb-1">📚</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_books'] }}</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Knihy ({{ $stats['published_books'] }} pub.)</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <div class="text-2xl mb-1">📖</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_chapters'] }}</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kapitoly</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <div class="text-2xl mb-1">✍️</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_words'], 0, ',', ' ') }}</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Celkom slov</p>
            </div>
        </div>

        <!-- Users by Role + System Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Users by Role -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-4">👥 Používatelia podľa rolí</h3>
                <div class="space-y-3">
                    @foreach($usersByRole as $role)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-700 dark:text-slate-300 capitalize">{{ $role->role }}</span>
                        <div class="flex items-center gap-3">
                            <div class="w-24 sm:w-32 bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-2 rounded-full" 
                                     style="width: {{ $stats['total_users'] > 0 ? ($role->count / $stats['total_users']) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white min-w-[2rem] text-right">{{ $role->count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-4">⚙️ Systémové informácie</h3>
                <div class="space-y-2">
                    @foreach($systemInfo as $key => $value)
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                        <span class="text-sm text-slate-600 dark:text-slate-400 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Books + Recent Users -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Books -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900 dark:text-white">🏆 Najčítanejšie knihy</h3>
                    <a href="{{ route('admin.books') }}" class="text-xs text-blue-600 hover:underline">Všetky knihy →</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($topBooks as $book)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <span class="text-xs font-bold text-slate-400 w-5">{{ $loop->iteration }}.</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white line-clamp-1">{{ $book->title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $book->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $book->view_count }}</p>
                            <p class="text-xs text-slate-400">prečítaní</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-slate-500">Zatiaľ žiadne knihy</div>
                    @endforelse
                </div>
            </div>

            <!-- Admin Users -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900 dark:text-white">🛡️ Administrátori</h3>
                    <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 hover:underline">Všetci users →</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($adminUsers as $admin)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $admin->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $admin->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            @if($admin->role === 'super_admin') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400
                            @else bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                            @endif">
                            {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-slate-500">Žiadni administrátori</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="font-semibold text-slate-900 dark:text-white">👥 Nedávni používatelia</h3>
                <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 hover:underline">Všetci používatelia →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Meno</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Email</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Rola</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Stav</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Registrovaný</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($recentUsers as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs px-2 py-1 rounded-full font-medium capitalize
                                    @if($user->role === 'super_admin') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400
                                    @elseif($user->role === 'admin') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                                    @else bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400
                                    @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if($user->is_active)
                                <span class="text-green-600 dark:text-green-400 text-xs font-medium">✅ Aktívny</span>
                                @else
                                <span class="text-red-600 dark:text-red-400 text-xs font-medium">❌ Blokovaný</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $user->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
