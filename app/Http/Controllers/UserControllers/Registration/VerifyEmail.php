<?php

namespace App\Http\Controllers\UserControllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmail extends Controller
{

    public function email_verification()
    {
        return redirect('/login')->with('status', 'We have sent a verification link to your email address. Please verify your account to login.');

    }

    // public function resendVerificationCode(Request $request)
    // {$request->user()->sendEmailVerificationNotification();

    //     return redirect()->route('verification.send')->with('status', 'A new verification code has been sent to your email address.');
    // }

    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect('/');
        }
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect('/login')->with('status', 'Email Verified Now you can login to your account.');
    }
}
