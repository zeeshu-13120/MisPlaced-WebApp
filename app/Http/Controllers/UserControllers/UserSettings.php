<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSettings extends Controller
{
    public function showProfileSettings()
    {
        $user = Auth::user();

        return view('Profile.settings', compact('user'));
    }
    public function updateInfo(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|min:10|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->hasFile('photo')) {
            $currentProfileImage = $user->photo;

            $profileImage = $request->file('photo');
            $filename = "user_id_" . $id . "_" . time() . '.' . $profileImage->getClientOriginalExtension();
            $path = $profileImage->storeAs('profile_photos', $filename, 'public');
            $user->photo = "/storage/profile_photos/" . $filename;

            // Delete the old profile image if it exists
            if ($currentProfileImage) {
                Storage::delete('public/profile_photos/' . $currentProfileImage);
            }
        }
        $user->save();
// Re-authenticate the user to update the Auth::user() data
        Auth::login($user);

        return redirect()->route('profile_settings.show')->with('global_status', 'Account Information Updated Successfully.');
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'zip_code' => 'numeric',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $user->city = $request->city;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;

        $user->save();

        return redirect()->back()->with('global_status', 'Address updated successfully')->with('tab', 'Address');
    }

    public function updatePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the authenticated user
        $user = User::findOrFail(Auth::user()->id);

        // Check if the current password matches
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
