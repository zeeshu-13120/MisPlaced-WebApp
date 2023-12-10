<?php

namespace App\Http\Controllers\AdminControllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Settings extends Controller
{
    public function settings()
    {
        $admin = Auth::guard('admin')->user();

        return view('AdminViews.Registration.settings', compact('admin'));
    }
    public function updateAccount(Request $request)
    { // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . auth()->guard('admin')->user()->id,
            'phone' => 'required|string|min:10|max:15',
            'profile_photo' => 'nullable|image|max:1024',
        ]);

        // Get the authenticated admin user
        $admin = auth()->guard('admin')->user();

        // Update the name, email, and phone fields
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->phone = $request->input('phone');

        // Handle profile photo upload, if provided
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/profile_photos', $filename);
            $admin->photo = "/storage/profile_photos/".$filename;
        }

        // Save the updated admin information
        $admin->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Account updated successfully!');
    }
    public function updatePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the authenticated admin user
        $admin = auth()->guard('admin')->user();

        // Check if the current password matches
        if (!Hash::check($request->input('current_password'), $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
