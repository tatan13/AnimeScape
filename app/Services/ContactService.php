<?php

namespace App\Services;

use App\Http\Requests\ContactRequest;
use App\Repositories\ContactRepository;

class ContactService
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     *
     */
    public function getContact($id)
    {
        return $this->contactRepository->getById($id);
    }

    /**
     *
     */
    public function getLatestContactList()
    {
        return $this->contactRepository->getLatestContactList();
    }

    /**
     *
     */
    public function createContact(ContactRequest $contact)
    {
        $this->contactRepository->createContact($contact);
    }
}