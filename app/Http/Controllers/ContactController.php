<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\ContactForm;

class ContactController extends Controller
{
    /**
     * 要望フォームを表示
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contacts = Contact::all();

        return view('contact', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * 要望を処理し，要望フォームにリダイレクト
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
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
