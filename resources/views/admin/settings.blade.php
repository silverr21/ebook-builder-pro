@extends('layouts.app')

@section('title', 'Nastavenia systému - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-600 hover:underline">← Admin</a>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">⚙️ Nastavenia systému</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Správa systémovej konfigurácie</p>
        </div>

        <!-- System Info -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">🖥️ Systémové informácie</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">PHP verzia</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ PHP_VERSION }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Laravel verzia</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ app()->version() }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Prostredie</p>
                    <p class="text-sm font-semibold capitalize mt-1 {{ app()->environment('production') ? 'text-amber-600' : 'text-green-600' }}">
                        {{ app()->environment() }}
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Debug mód</p>
                    <p class="text-sm font-semibold mt-1 {{ config('app.debug') ? 'text-amber-600' : 'text-green-600' }}">
                        {{ config('app.debug') ? 'Zapnutý' : 'Vypnutý' }}
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Databáza</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ config('database.default') }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Cache driver</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ config('cache.default') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">🚀 Rýchle akcie</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <span class="text-lg mb-1 block">🗑️</span>
                    <span class="text-sm font-medium text-amber-900 dark:text-amber-100">Vyčistiť cache</span>
                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Spustite <code class="text-xs px-1 bg-amber-100 dark:bg-amber-800 rounded">php artisan optimize:clear</code> v termináli</p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <span class="text-lg mb-1 block">🔄</span>
                    <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Spustiť migrácie</span>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-0.5">Spustite <code class="text-xs px-1 bg-blue-100 dark:bg-blue-800 rounded">php artisan migrate</code> v termináli</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="block p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                    <span class="text-lg mb-1 block">📊</span>
                    <span class="text-sm font-medium text-green-900 dark:text-green-100">Admin Dashboard</span>
                    <p class="text-xs text-green-700 dark:text-green-300 mt-0.5">Späť na hlavný prehľad</p>
                </a>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <span class="text-2xl">ℹ️</span>
                <div>
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Upozornenie</h3>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Niektoré systémové nastavenia sú dostupné len priamo v <code class="px-1.5 py-0.5 bg-blue-100 dark:bg-blue-800 rounded text-xs font-mono">.env</code> súbore.
                        Pre zmenu základnej konfigurácie upravte .env súbor a potom vyčistite cache.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
