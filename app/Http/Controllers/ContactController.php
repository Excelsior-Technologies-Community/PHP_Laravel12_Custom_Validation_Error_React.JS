<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(ContactRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'message' => $request->message,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Contact saved successfully!',
                'data' => $contact
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'latest');
        
        $contacts = Contact::when($search, function($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('message', 'LIKE', "%{$search}%");
        })->when($sort === 'oldest', function($query) {
            return $query->oldest();
        })->latest()->paginate(10);

        // Add stats for dashboard
        $stats = [
            'total' => Contact::count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('contact_list', compact('contacts', 'stats', 'search', 'sort'));
    }
    
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }
    
    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contact!'
            ], 500);
        }
    }
    
    public function bulkDelete(Request $request)
    {
        try {
            Contact::whereIn('id', $request->ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Contacts deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contacts!'
            ], 500);
        }
    }
    
    public function export()
    {
        $contacts = Contact::all(['name', 'email', 'message', 'created_at']);
        
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Name', 'Email', 'Message', 'Submitted At']);
        
        foreach ($contacts as $contact) {
            fputcsv($csv, [
                $contact->name,
                $contact->email,
                $contact->message,
                $contact->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        return response($csvContent)
            ->withHeaders([
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="contacts_' . date('Y-m-d') . '.csv"',
            ]);
    }
}