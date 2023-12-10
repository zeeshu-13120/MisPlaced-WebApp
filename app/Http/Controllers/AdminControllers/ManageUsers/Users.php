<?php

namespace App\Http\Controllers\AdminControllers\ManageUsers;

use App\Http\Controllers\Controller;
use App\Mail\NewUserAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class Users extends Controller
{
    public function userList()
    {

        // Pass the users data to the view
        return view('AdminViews.ManageUsers.userList');
    }
 
    public function usersReport()
    {
        $usersReport = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Pass the users data to the view
        return view('AdminViews.ManageUsers.report', compact('usersReport'));
    }

    public function updateUser(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => [
                'required',
                'digits_between:10,15',
                Rule::unique('users', 'phone')->ignore($id),
            ],
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);
        $currentProfileImage = $user->photo;

        if ($request->hasFile('photo')) {
            $profileImage = $request->file('photo');
            $filename = "user_id_" . $id . "_" . time() . '.' . $profileImage->getClientOriginalExtension();
            $path = $profileImage->storeAs('profile_photos', $filename, 'public');
            $user->photo = "/storage/profile_photos/" . $filename;

            // Delete the old profile image if it exists
            if ($currentProfileImage) {
                if ($currentProfileImage != "/storage/profile_photos/default_profile_photo.jpg") {
                    Storage::delete('public/profile_photos/' . $currentProfileImage);}
            }
        }

        // Update user data excluding the photo column
        $user->update($request->except('photo'));

        // Save the changes
        if ($user->save()) {
            // Redirect to the users list with a success message
            return redirect()->route('users.list')->with('admin_status', 'User information updated successfully');
        }
        // Redirect to the users list with a Error message
        return redirect()->route('users.list')->with('admin_error', 'Error! Unable to update user information');
    }

    public function sendPasswordResetLink(Request $request, $id)
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
        return response()->json(['message' => 'Password reset link sent successfully.']);
    }

    public function getUserData($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }
    public function getUsers(Request $request)
    {
        // Get the columns to display
        $columns = ['id', 'first_name', 'last_name', 'email', 'banned', 'email_verified_at', 'phone', 'photo'];

        // Start building the query
        $query = DB::table('users')->select($columns);

        // Get the total number of records
        $filteredCount = $total = $query->count();

        // Apply search filter if necessary
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
            $filteredCount = $query->count();
        }

        // Apply order if necessary
        if ($request->has('order')) {
            $orderCol = $columns[$request->input('order')[0]['column']];
            $orderDir = $request->input('order')[0]['dir'];
            $query->orderBy($orderCol, $orderDir);
        }

        //Applying Pagination
        $perPage = $request->input('length');
        $page = $request->input('start') / $perPage + 1;

        $query->skip(($page - 1) * $perPage)->take($perPage);

        // Get the records
        $result = $query->get();

        //Modeify the records
        $data = [];

        foreach ($result as $row) {
            $action = '
                <a data-id="' . $row->id . '" class="btn btn-success btn-sm show-user-details">
                    <i class="bi bi-eye"></i>
                </a>
                <a data-id="' . $row->id . '" href="#" class="btn btn-primary btn-sm show-user-edit-modal">
                    <i class="bi bi-pencil-square"></i>
                </a> ';

            $statusColor = "orange";
            if ($row->banned == true) {
                $statusColor = "red";
            } else if (!empty($row->email_verified_at)) {
                $statusColor = "green";

            }

            $status = '<span class="status-dot" style="background-color:' . $statusColor . '"></span>';

            $photo = '<img src=' . $row->photo . ' width="50">';
            $data[] = [
                'id' => $row->id,
                'photo' => $photo,
                'name' => $row->first_name . " " . $row->last_name,
                'email' => $row->email,
                'phone' => $row->phone,
                'status' => $status,
                'action' => $action,
            ];
        }

        // Prepare the response
        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $filteredCount,
            'data' => $data,
        ];

        return response()->json($response);

    }

}
