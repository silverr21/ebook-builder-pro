<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        return view('pages.profile', compact('user'));
    }

    public function updateInfo(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil bol úspešne aktualizovaný!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Heslo bolo úspešne zmenené!');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user = auth()->user();

        // Delete old avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar bol úspešne aktualizovaný!');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Avatar bol odstránený.');
    }

    public function updateAiSettings(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'ai_api_key' => ['nullable', 'string', 'max:255'],
            'ai_model' => ['nullable', 'string', 'max:100'],
            'ai_enabled' => ['boolean'],
            'ai_provider' => ['nullable', 'string', 'in:openai,anthropic,gemini'],
            'ai_temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
            'ai_max_tokens' => ['nullable', 'integer', 'min:100', 'max:16000'],
            'ai_timeout' => ['nullable', 'integer', 'min:10', 'max:300'],
            'ai_language' => ['nullable', 'string', 'in:sk,cs,en,de,pl,hu'],
            'ai_api_url' => ['nullable', 'string', 'max:500'],
        ]);

        // Only update API key if a new one was provided (not masked)
        if ($request->filled('ai_api_key') && !str_starts_with($request->ai_api_key, '••••')) {
            $user->ai_api_key = $validated['ai_api_key'];
        }

        $user->ai_model = $validated['ai_model'] ?? $user->ai_model;
        $user->ai_enabled = $request->boolean('ai_enabled', true);
        $user->ai_provider = $validated['ai_provider'] ?? $user->ai_provider;
        $user->ai_temperature = $validated['ai_temperature'] ?? $user->ai_temperature;
        $user->ai_max_tokens = $validated['ai_max_tokens'] ?? $user->ai_max_tokens;
        $user->ai_timeout = $validated['ai_timeout'] ?? $user->ai_timeout;
        $user->ai_language = $validated['ai_language'] ?? $user->ai_language;
        $user->ai_api_url = $validated['ai_api_url'] ?? $user->ai_api_url;
        $user->save();

        return back()->with('success', 'AI nastavenia boli uložené!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('success', 'Účet bol zmazaný.');
    }
}
