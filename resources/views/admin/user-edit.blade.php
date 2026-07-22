@extends('layouts.app')

@section('title', 'Upraviť používateľa - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:underline">← Späť na zoznam</a>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mt-2">✏️ Upraviť používateľa</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $user->name }} ({{ $user->email }})</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Meno</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-blue-700 dark:text-blue-300">Nechajte prázdne ak nechcete meniť heslo.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nové heslo</label>
                        <input type="password" name="password" minlength="8"
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Potvrdenie hesla</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Rola</label>
                        <select name="role" required
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ $user->role === $role->value ? 'selected' : '' }}>{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3 pt-7">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Aktívny účet</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Overený email</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                        💾 Uložiť zmeny
                    </button>
                    <a href="{{ route('admin.users') }}" class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Zrušiť
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
