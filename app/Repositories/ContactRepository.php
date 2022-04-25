<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Contact::class;
    }

    /**
     * 要望リストを降順に並べて取得
     *
     * @return Collection<int,Contact> | Collection<null>
     */
    public function getLatestContactList()
    {
        return Contact::latest()->get();
    }

    /**
     * 要望を作成
     *
     * @param ContactRequest $contact
     * @return void
     */
    public function createContact(ContactRequest $contact)
    {
        Contact::create($contact->validated());
    }
}
