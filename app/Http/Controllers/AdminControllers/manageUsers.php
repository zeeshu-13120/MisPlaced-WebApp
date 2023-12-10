<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class manageUsers extends Controller
{
    public function addUser()
    {
        return view('AdminViews.ManageUsers.addusers');
    }

    public function usersList()
    {
        return view('AdminViews.ManageUsers.userList');

    }
    public function banedUsers()
    {
        return view('AdminViews.ManageUsers.banedUsers');

    }
}
