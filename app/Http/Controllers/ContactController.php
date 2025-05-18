<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\ContactMessage;

class ContactController extends Controller
{

    public function send(Request $request)
{
    $data = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'message' => 'required|string',
    ]);

    Mail::to('robotshopacademy@gmail.com')->send(new ContactMessage($data));

    return back()->with('success', 'Votre message a été envoyé avec succès !');

}
}



