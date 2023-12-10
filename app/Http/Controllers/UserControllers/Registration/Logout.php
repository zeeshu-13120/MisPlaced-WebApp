<?php

namespace App\Http\Controllers\UserControllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class Logout extends Controller
{
    public function logout()
    {
        // Clear all sessions
        Session::regenerate(true);

// Optional: Flush any flashed session data
        Session::flush();
        Auth::logout();

        return redirect('/');
    }
}
