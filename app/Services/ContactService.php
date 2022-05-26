<?php

namespace App\Services;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Collection;
use Mail;

class ContactService
{
    private ContactRepository $contactRepository;

    /**
     * コンストラクタ
     *
     * @param ContactRepository $contactRepository
     * @return void
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * contact_idから要望を取得
     *
     * @param int $contact_id
     * @return Contact
     */
    public function getContact($contact_id)
    {
        return $this->contactRepository->getById($contact_id);
    }

    /**
     * 要望リストを降順に並べて取得
     *
     * @return Collection<int,Contact> | Collection<null>
     */
    public function getLatestContactList()
    {
        return $this->contactRepository->getLatestContactList();
    }

    /**
     * 要望を作成
     *
     * @param ContactRequest $contact
     * @return void
     */
    public function createContact(ContactRequest $contact)
    {
        $this->contactRepository->createContact($contact);
        $this->sendMailWhenContactRequest();
    }

    /**
     * 要望フォーム投稿時管理者にメールで通知
     *
     * @return void
     */
    public function sendMailWhenContactRequest()
    {
        if (env('APP_ENV') == 'production') {
            $data = [];

            Mail::send('emails.contact_email', $data, function ($message) {
                $message->to(config('mail.from.address'), config('app.name'))
                ->subject('要望投稿');
            });
        }
    }
}
