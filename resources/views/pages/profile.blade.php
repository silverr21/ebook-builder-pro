@extends('layouts.app')

@section('title', 'Profil - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8" x-data="{ activeTab: 'info', showDeleteModal: false }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">👤 Môj profil</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Spravujte svoje osobné údaje a nastavenia účtu</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-300 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar / Avatar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 text-center sticky top-8">
                    <!-- Avatar -->
                    <div class="relative mx-auto w-28 h-28 mb-4">
                        <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-4xl overflow-hidden shadow-lg">
                            @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-white font-bold text-3xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <label for="avatar-upload" class="absolute -bottom-1 -right-1 w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center cursor-pointer hover:bg-blue-700 transition shadow-md border-2 border-white dark:border-slate-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                        <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="avatar-upload" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                        </form>
                    </div>

                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full capitalize">
                            {{ $user->role }}
                        </span>
                        @if($user->email_verified_at)
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                            ✅ Overený
                        </span>
                        @endif
                    </div>

                    <div class="mt-6 space-y-1">
                        <div class="flex justify-between text-sm py-2">
                            <span class="text-slate-500 dark:text-slate-400">Knihy</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $user->books()->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm py-2 border-t border-slate-100 dark:border-slate-700">
                            <span class="text-slate-500 dark:text-slate-400">Členom od</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>

                    <!-- Delete account -->
                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button @click="showDeleteModal = true" 
                                class="w-full px-4 py-2 border border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 text-sm font-medium rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            🗑️ Zmazať účet
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Tabs -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex border-b border-slate-200 dark:border-slate-700">
                        <button @click="activeTab = 'info'" 
                                class="flex-1 px-6 py-4 text-sm font-medium transition relative"
                                :class="activeTab === 'info' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            📝 Osobné údaje
                            <div x-show="activeTab === 'info'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
                        </button>
                        <button @click="activeTab = 'password'" 
                                class="flex-1 px-6 py-4 text-sm font-medium transition relative"
                                :class="activeTab === 'password' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            🔒 Zmeniť heslo
                            <div x-show="activeTab === 'password'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
                        </button>
                    </div>

                    <!-- Tab: Personal Info -->
                    <div x-show="activeTab === 'info'" class="p-6">
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Meno</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Telefón</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="+421 901 234 567">
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                                    💾 Uložiť zmeny
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab: Change Password -->
                    <div x-show="activeTab === 'password'" class="p-6" style="display: none;">
                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6 max-w-md">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Súčasné heslo</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Zadajte súčasné heslo">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nové heslo</label>
                                <input type="password" name="password" required minlength="8"
                                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Minimálne 8 znakov">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Potvrdenie nového hesla</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Zopakujte nové heslo">
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                                <p class="text-sm text-blue-700 dark:text-blue-300 flex items-center gap-2">
                                    🔒 Po zmene hesla zostanete prihlásený(á).
                                </p>
                            </div>

                            <button type="submit" 
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                                🔑 Zmeniť heslo
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mt-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">📊 Informácie o účte</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Rola</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1 capitalize">{{ $user->role }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Registrovaný</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Email overený</p>
                            <p class="text-sm font-semibold mt-1 {{ $user->email_verified_at ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $user->email_verified_at ? '✅ Áno' : '❌ Nie' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase font-medium">Posledná úprava</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white mt-1">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Account Modal -->
    <div x-show="showDeleteModal" @click.self="showDeleteModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 w-full max-w-md shadow-2xl">
            <div class="text-center mb-6">
                <div class="text-4xl mb-3">⚠️</div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Zmazať účet</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                    Táto akcia je nenávratná. Všetky vaše knihy a dáta budú natrvalo odstránené.
                </p>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-4">
                @csrf @method('DELETE')
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pre potvrdenie zadajte heslo</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Vaše aktuálne heslo">
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
                        🗑️ Natrvalo zmazať
                    </button>
                    <button type="button" @click="showDeleteModal = false"
                            class="px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Zrušiť
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
