<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120',
            'phone' => 'nullable|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($data);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'বার্তা পাঠানো হয়েছে']);
        }

        return back()->with('toast', '✅ আপনার বার্তা পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করবো।');
    }
}
