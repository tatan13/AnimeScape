<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Services\ContactService;

class ContactController extends Controller
{
    private ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * 要望フォームを表示
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $contacts = $this->contactService->getLatestContactList();
        return view('contact', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * 要望を処理し，要望フォームにリダイレクト
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(ContactRequest $request)
    {
        $this->contactService->createContact($request);
        return redirect(route('contact.show'))->with('flash_message', '要望の投稿が完了しました。');
    }
}
