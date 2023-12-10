<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($validatedData);

        return redirect()->back()->with('success', 'Your message has been submitted successfully!');
    }

    public function showAllMessages()
    {
        $contacts = Contact::all();

        return view('AdminViews.Contact.index', compact('contacts'));
    }
    public function deleteMessage($id)
{
    $contact = Contact::find($id);

    if ($contact) {
        $contact->delete();

        return response()->json(['message' => 'Message deleted successfully.']);
    } else {
        return response()->json(['error' => 'Message not found.']);
    }
}
}
