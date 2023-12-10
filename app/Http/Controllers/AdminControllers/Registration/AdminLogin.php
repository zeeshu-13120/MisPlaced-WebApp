<?php

namespace App\Http\Controllers\AdminControllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminLogin extends Controller
{

    public function login()
    {
        return view('AdminViews.Registration.login');
    }
    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            Auth::guard('admin')->user()->last_login = now();
            Auth::guard('admin')->user()->save();
            // If successful, redirect to the admin dashboard or intended page
            return redirect()->intended(route('dashboard.view'));
        } else {
            // If unsuccessful, redirect back with an error message
            return redirect()->back()->withInput($request->only('email'))->withErrors(['error' => 'Invalid email or password']);
        }
    }



    public function logout()
{

    Auth::guard('admin')->logout();

    return redirect('/admin/login');
}

}
