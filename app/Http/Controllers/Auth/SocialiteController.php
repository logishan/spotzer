<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'email_verified_at' => now(),
                'password' => bcrypt(str_random(16)), // Random password as it won't be used
            ]);

            Auth::login($user);

            return redirect('/dashboard')->with('success', 'Logged in successfully!');
            
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed. Please try again.');
        }
    }
} 