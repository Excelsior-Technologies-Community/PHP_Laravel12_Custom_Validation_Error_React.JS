<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(ContactRequest $request)
    {
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }

    public function list(Request $request)
    {
        $search = $request->get('search');
        
        $contacts = Contact::when($search, function($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
        })->latest()->paginate(5);

        return view('contact_list', compact('contacts')); 
    }
}