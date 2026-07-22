<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConfirmPasswordController extends Controller
{
    public function showConfirmForm(): View
    {
        return view('auth.passwords.confirm');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Auth::validate(['email' => $user->email, 'password' => $request->password])) {
            return back()->withErrors([
                'password' => 'Zadané heslo nie je správne.',
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended('/dashboard');
    }
}
