<?php

namespace App\Packages\ContactBundle\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ContactBundle\Models\ContactInquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact-bundle::contact.index');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactInquiry::create($validated);

        return redirect()->route('contact.index')->with('success', 'Thank you! Your message has been received. We will get back to you soon.');
    }
}
