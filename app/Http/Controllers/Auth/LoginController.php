<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        Log::info('Login attempt', ['email' => $credentials['email']]);

        // Check if user exists first
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::info('User not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            Log::info('Login successful', ['user_id' => $user->id]);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        Log::info('Login failed - password mismatch', ['user_id' => $user->id]);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}