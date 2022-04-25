<?php

namespace App\Services;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Collection;

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
     * idから要望を取得
     *
     * @param int $id
     * @return Contact
     */
    public function getContact($id)
    {
        return $this->contactRepository->getById($id);
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
    }
}
