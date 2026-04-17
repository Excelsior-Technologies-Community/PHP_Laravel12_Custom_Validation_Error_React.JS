<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Custom validation
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:contacts,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'message' => 'required|min:10',
        ],[
            'name.required' => 'Name is mandatory',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be 6 characters',
            'confirm_password.same' => 'Password does not match',
            'message.required' => 'Message cannot be empty',
            'message.min' => 'Message must be 10 characters',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }

      // List all submitted contacts
    public function list()
    {
        $contacts = Contact::all();
        return view('contact_list', compact('contacts')); 
    }
}
