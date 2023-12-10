<?php

namespace App\Http\Controllers\UserControllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLogin extends Controller
{
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleLoginCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception$e) {

            return redirect('/login')->withErrors(['Unable to authenticate with Google.Try Again']);
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            // Log the user in
            Auth::login($existingUser, true);
            if (Auth::user()->banned == true) {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'error' => 'Your account has been banned.Contact Support',
                ]);
                die();
            }
            Auth::user()->last_login = now();
            Auth::user()->save();
        } else {

// Create a new user and log them in
            $newUser = new User;
            $newUser->first_name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->google_id = $user->getId();
            $newUser->photo = $user->getAvatar();
            $newUser->email_verified_at = now();
            $newUser->last_login = now();
            $newUser->save();

            Auth::login($newUser, true);
        }

        return redirect()->intended('/');
    }
}
