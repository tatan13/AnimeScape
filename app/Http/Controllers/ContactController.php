<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\ContactForm;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        return view('contact', [
            'contacts' => $contacts,
        ]);
    }

    public function post(ContactForm $request)
    {
        if (strcmp($request->auth, "にんしょう") == 0) {
            $contact = new Contact();
            if (!is_null($request->name)) {
                $contact->name = $request->name;
            }
            $contact->comment = $request->comment;
            $contact->save();
        }

        return redirect(route('contact.index'));
    }
}
