<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;

class ContactRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return Contact::class;
    }

    /**
     *
     */
    public function getLatestContactList()
    {
        return Contact::latest()->get();
    }

    /**
     *
     */
    public function createContact(ContactRequest $contact)
    {
        Contact::create($contact->validated());
    }
}