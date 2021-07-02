<?php

namespace App\Contracts\Drivers;

use Illuminate\Support\Collection;

interface FileReaderDriver
{
    /**
     * Set driver content
     *
     * @param Collection $fileContent
     * @return FileReaderDriver
     */
    public function setContent(Collection $fileContent): FileReaderDriver;

    /**
     * Get driver content
     *
     * @return Collection
     */
    public function getContent(): Collection;
}
