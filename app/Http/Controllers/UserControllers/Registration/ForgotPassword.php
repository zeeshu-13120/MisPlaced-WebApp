<?php

namespace App\Http\Controllers\UserControllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPassword extends Controller
{

    public function forgot_password()
    {

        return view('Registration.forgot_password');
    }

    public function sendPasswordResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');
        return view('Registration.set_password', compact('email', 'token'));
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        });

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Your password has been reset. Please login with your new password.');
        } else {
            return back()->withErrors(['email' => trans($response)]);
        }

    }
}
