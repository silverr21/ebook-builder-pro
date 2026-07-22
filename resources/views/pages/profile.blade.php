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
                        </button>                <button @click="activeTab = 'password'" 
                        class="flex-1 px-6 py-4 text-sm font-medium transition relative"
                        :class="activeTab === 'password' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            🔒 Zmeniť heslo
                            <div x-show="activeTab === 'password'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
                        </button>
                        <button @click="activeTab = 'ai'" 
                                class="flex-1 px-6 py-4 text-sm font-medium transition relative"
                                :class="activeTab === 'ai' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                            🤖 AI Nastavenia
                            <div x-show="activeTab === 'ai'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
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

                    <!-- Tab: AI Settings (Extended) -->
                    <div x-data="aiSettings()" x-init="init()" x-show="activeTab === 'ai'" class="p-6" style="display: none;">
                        <form action="{{ route('profile.ai.settings') }}" method="POST" class="space-y-6 max-w-2xl">
                            @csrf
                            @method('PUT')

                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl">🤖</span>
                                    <div>
                                        <h4 class="font-semibold text-purple-900 dark:text-purple-100">AI Asistent — Rozšírené nastavenia</h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-300 mt-1">Podpora OpenAI (GPT), Anthropic (Claude) a Google (Gemini). Každý používateľ si môže nastaviť vlastný API kľúč.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Provider -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">AI Provider</label>
                                    <select name="ai_provider" x-model="provider" @change="updateModels()"
                                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                        <option value="openai" {{ $user->ai_provider === 'openai' ? 'selected' : '' }}>OpenAI (GPT-4, GPT-3.5)</option>
                                        <option value="anthropic" {{ $user->ai_provider === 'anthropic' ? 'selected' : '' }}>Anthropic (Claude 3)</option>
                                        <option value="gemini" {{ $user->ai_provider === 'gemini' ? 'selected' : '' }}>Google (Gemini)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">AI Model</label>
                                    <select name="ai_model"
                                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                        <option x-show="provider === 'openai'" value="gpt-4-turbo" {{ $user->ai_model === 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo (výkonný)</option>
                                        <option x-show="provider === 'openai'" value="gpt-4o" {{ $user->ai_model === 'gpt-4o' ? 'selected' : '' }}>GPT-4o (najnovší)</option>
                                        <option x-show="provider === 'openai'" value="gpt-4o-mini" {{ $user->ai_model === 'gpt-4o-mini' ? 'selected' : '' }}>GPT-4o Mini (rýchly, lacný)</option>
                                        <option x-show="provider === 'openai'" value="gpt-3.5-turbo" {{ $user->ai_model === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (najlacnejší)</option>
                                        <option x-show="provider === 'anthropic'" value="claude-3-opus-20240229" {{ $user->ai_model === 'claude-3-opus-20240229' ? 'selected' : '' }}>Claude 3 Opus (najvýkonnejší)</option>
                                        <option x-show="provider === 'anthropic'" value="claude-3-sonnet-20240229" {{ $user->ai_model === 'claude-3-sonnet-20240229' ? 'selected' : '' }}>Claude 3 Sonnet (vyvážený)</option>
                                        <option x-show="provider === 'anthropic'" value="claude-3-haiku-20240307" {{ $user->ai_model === 'claude-3-haiku-20240307' ? 'selected' : '' }}>Claude 3 Haiku (rýchly)</option>
                                        <option x-show="provider === 'anthropic'" value="claude-3-5-sonnet-20241022" {{ $user->ai_model === 'claude-3-5-sonnet-20241022' ? 'selected' : '' }}>Claude 3.5 Sonnet (najnovší)</option>
                                        <option x-show="provider === 'gemini'" value="gemini-1.5-pro" {{ $user->ai_model === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro</option>
                                        <option x-show="provider === 'gemini'" value="gemini-1.5-flash" {{ $user->ai_model === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (rýchly)</option>
                                        <option x-show="provider === 'gemini'" value="gemini-2.0-flash-exp" {{ $user->ai_model === 'gemini-2.0-flash-exp' ? 'selected' : '' }}>Gemini 2.0 Flash (experimentálny)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- API Key + Enable -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                        <span x-text="provider === 'openai' ? 'OpenAI' : (provider === 'anthropic' ? 'Anthropic Claude' : 'Google Gemini')"></span> API Kľúč
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="ai_api_key" value="{{ $user->ai_api_key ? '••••••••••••••••' : '' }}" 
                                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition font-mono text-sm"
                                            placeholder="sk-... alebo claude-...">
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                                        🔑 Získajte kľúč na 
                                        <a x-show="provider === 'openai'" href="https://platform.openai.com/api-keys" target="_blank" class="text-purple-600 hover:text-purple-700 underline">platform.openai.com</a>
                                        <a x-show="provider === 'anthropic'" href="https://console.anthropic.com/" target="_blank" class="text-purple-600 hover:text-purple-700 underline">console.anthropic.com</a>
                                        <a x-show="provider === 'gemini'" href="https://aistudio.google.com/apikey" target="_blank" class="text-purple-600 hover:text-purple-700 underline">aistudio.google.com</a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">&nbsp;</label>
                                    <label class="flex items-center gap-3 px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition h-[42px]">
                                        <input type="checkbox" name="ai_enabled" value="1" {{ $user->ai_enabled ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Povoliť AI</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Advanced Settings -->
                            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-medium text-slate-900 dark:text-white">🔧 Rozšírené nastavenia</h4>
                                    <button type="button" @click="showAdvanced = !showAdvanced" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                                        <span x-text="showAdvanced ? 'Skryť' : 'Zobraziť'"></span>
                                    </button>
                                </div>

                                <div x-show="showAdvanced" class="space-y-5">
                                    <!-- Temperature & Max Tokens -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Teplota (kreativita): <span class="font-mono" x-text="temperature"></span></label>
                                            <input type="range" name="ai_temperature" x-model="temperature" min="0" max="2" step="0.1"
                                                class="w-full accent-purple-600">
                                            <div class="flex justify-between text-xs text-slate-400">
                                                <span>Presný (0)</span>
                                                <span>Vyvážený (1)</span>
                                                <span>Kreatívny (2)</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Max tokenov</label>
                                            <select name="ai_max_tokens"
                                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                                <option value="500" {{ $user->ai_max_tokens === 500 ? 'selected' : '' }}>500 (krátke odpovede)</option>
                                                <option value="1000" {{ $user->ai_max_tokens === 1000 ? 'selected' : '' }}>1 000 (štandard)</option>
                                                <option value="2000" {{ $user->ai_max_tokens === 2000 ? 'selected' : '' }}>2 000 (dlhšie texty)</option>
                                                <option value="4000" {{ $user->ai_max_tokens === 4000 ? 'selected' : '' }}>4 000 (dlhé kapitoly)</option>
                                                <option value="8000" {{ $user->ai_max_tokens === 8000 ? 'selected' : '' }}>8 000 (veľmi dlhé)</option>
                                                <option value="16000" {{ $user->ai_max_tokens === 16000 ? 'selected' : '' }}>16 000 (maximálne)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Timeout & Language -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Timeout (sekundy)</label>
                                            <input type="number" name="ai_timeout" value="{{ $user->ai_timeout ?? 60 }}" min="10" max="300"
                                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jazyk výstupu</label>
                                            <select name="ai_language"
                                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                                <option value="sk" {{ $user->ai_language === 'sk' ? 'selected' : '' }}>🇸🇰 Slovenčina</option>
                                                <option value="cs" {{ $user->ai_language === 'cs' ? 'selected' : '' }}>🇨🇿 Čeština</option>
                                                <option value="en" {{ $user->ai_language === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                                <option value="de" {{ $user->ai_language === 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                                                <option value="pl" {{ $user->ai_language === 'pl' ? 'selected' : '' }}>🇵🇱 Polski</option>
                                                <option value="hu" {{ $user->ai_language === 'hu' ? 'selected' : '' }}>🇭🇺 Magyar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Custom API URL -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                            Vlastná API URL <span class="text-xs text-slate-400">(voliteľné, pre self-hosted modely)</span>
                                        </label>
                                        <input type="url" name="ai_api_url" value="{{ $user->ai_api_url }}" 
                                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition font-mono text-sm"
                                            placeholder="https://api.openai.com/v1">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                                <p class="text-sm text-blue-700 dark:text-blue-300 flex items-start gap-2">
                                    <span>💡</span>
                                    <span>API kľúč je uložený bezpečne v databáze. Podporované provider-y: OpenAI (GPT-4, GPT-3.5), Anthropic (Claude 3), Google (Gemini). Pre OpenAI použite kľúč začínajúci na <code class="font-mono bg-blue-100 dark:bg-blue-800 px-1 rounded">sk-</code>, pre Anthropic <code class="font-mono bg-blue-100 dark:bg-blue-800 px-1 rounded">sk-ant-</code>.</span>
                                </p>
                            </div>

                            <button type="submit" 
                                    class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-lg shadow-purple-500/25">
                                💾 Uložiť AI nastavenia
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

<script>
function aiSettings() {
    return {
        showAdvanced: false,
        provider: '{{ $user->ai_provider ?? "openai" }}',
        temperature: '{{ $user->ai_temperature ?? "0.7" }}',
        
        init() {},
        
        updateModels() {
            // Provider change handled by x-show on options
        }
    }
}
</script>
@endsection
