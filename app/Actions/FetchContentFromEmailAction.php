<?php

namespace App\Actions;

use App\Contracts\Services\FileReaderService;
use App\Contracts\Services\EmailService;

class FetchContentFromEmailAction
{
    public function __construct(
        protected FileReaderService $fileReaderService,
        protected EmailService $emailService,
    )
    {
    }

    public function __invoke(
        array $emailCredentials,
        array $emailParams,
        string $fileName
    )
    {
        // starts a email session

        // try to find the specific email
        // try to locate the file
        // fetch data from file
        // save fetched data into the database
    }
}
