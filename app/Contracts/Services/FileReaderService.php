<?php

namespace App\Contracts\Services;

use App\Contracts\Drivers\FileReaderDriver;
use Illuminate\Support\Collection;

interface FileReaderService
{
    /**
     * File fields separator
     *
     * @var string
     */
    const SEPARATOR = '__SEPARATOR__';

    /**
     * Set file being processed by the service
     *
     * @param string $filePath
     * @return FileReaderService
     */
    public function setFile(string $filePath): FileReaderService;

    /**
     * Set the driver use to parse the file
     *
     * @param FileReaderDriver $driver
     * @return FileReaderService
     */
    public function applyDriver(FileReaderDriver $driver): FileReaderService;

    /**
     * Load file content into the memory
     *
     * @return FileReaderService
     */
    public function loadContent(): FileReaderService;

    /**
     * Parse de file content into the data array
     *
     * @return array
     */
    public function getContent(): Collection;
}
