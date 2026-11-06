<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Show the public Contact Us form (BNSP Requirement).
     */
    public function form()
    {
        return view('pages.contact');
    }

    /**
     * Store a message from the Contact Us form into the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'message.required' => 'Pesan aduan atau pertanyaan wajib diisi.',
            'message.min' => 'Pesan minimal terdiri dari 5 karakter.',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return redirect()->route('contact')->with('success', 'Terima kasih! Pesan Anda telah berhasil terkirim ke administrator Readowl.');
    }
}
