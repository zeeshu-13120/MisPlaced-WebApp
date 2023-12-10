<?php

namespace App\Http\Controllers\UserControllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class Singup extends Controller
{

    public function signup()
    {

        return view('Registration.signup');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:10,15|unique:users,phone',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => bcrypt($validatedData['password']),
        ]);

        if ($user) {
            event(new Registered($user));
            return redirect()->route('verification.send');
        } else {
            return redirect()->back()->with('error', "Error Try Again.");
        }

    }

}
