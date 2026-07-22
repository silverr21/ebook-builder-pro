@extends('layouts.app')

@section('title', 'Správa používateľov - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-600 hover:underline">← Admin</a>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">👥 Správa používateľov</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Spravujte všetkých používateľov systému</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                ✨ Nový používateľ
            </a>
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
                    <input type="text" name="search" placeholder="Hľadať podľa mena alebo emailu..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <select name="role" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    <option value="">Všetky role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>{{ $role->label() }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                    🔍 Filtrovať
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50">
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Používateľ</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Email</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Rola</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Stav</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Knihy</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Registrovaný</th>
                            <th class="text-right px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Akcie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium capitalize
                                    @if($user->role === 'super_admin') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400
                                    @elseif($user->role === 'admin') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                                    @elseif(in_array($user->role, ['author', 'editor'])) bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                    @else bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400
                                    @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                <span class="text-green-600 dark:text-green-400 text-xs font-medium">✅ Aktívny</span>
                                @else
                                <span class="text-red-600 dark:text-red-400 text-xs font-medium">❌ Blokovaný</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $user->books()->count() }}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                        ✏️ Upraviť
                                    </a>
                                    @if(!$user->isAdmin() || auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Zmazať používateľa {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                            🗑️ Zmazať
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
