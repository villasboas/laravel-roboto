<?php

namespace App\Services;

use App\Contracts\Services\FileReaderService as FileReaderServiceContract;
use App\Contracts\Drivers\FileReaderDriver;
use Illuminate\Support\Collection;
use Spatie\PdfToText\Pdf;
use Exception;

class FileReaderService implements FileReaderServiceContract
{
    /**
     * Path to the file being parsed
     *
     * @var string
     */
    protected string $filePath;

    /**
     * Driver used to parse the file content
     *
     * @var FileReaderDriver
     */
    protected FileReaderDriver $driver;

    /**
     * File content
     *
     * @var Collection
     */
    protected Collection $fileContent;

    /**
     * Set file being read
     *
     * @param string $filePath
     * @return FileReaderService
     */
    public function setFile(string $filePath): FileReaderService
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Set driver used to process the file
     *
     * @param FileReaderDriver $driver
     * @return FileReaderService
     */
    public function applyDriver(FileReaderDriver $driver): FileReaderService
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Load file content
     *
     * @return FileReaderService
     */
    public function loadContent(): FileReaderService
    {
        if (!$this->filePath || !file_exists($this->filePath)) {
            throw new Exception(__('You must specify a file to to read'));
        }

        $content = Pdf::getText($this->filePath, config('services.pdf.bin'), [
            'layout'
        ]);

        $this->fileContent = $this->sanitize($content);


        return $this;
    }

    /**
     * Get file content
     *
     * @return Collection
     */
    public function getContent(): Collection
    {
        if (isset($this->driver)) {
            return $this->driver->setContent($this->fileContent)->getContent();
        }

        return $this->fileContent;
    }

    /**
     * Sanitize file content
     *
     * @param string $fileContent
     * @return Collection
     */
    public function sanitize(string $fileContent): Collection
    {
        return collect(explode(PHP_EOL, $fileContent))
        ->filter(fn($partial) => $partial)
        ->map(fn($line) => trim(preg_replace('/\s\s+/', static::SEPARATOR, $line)))
        ->map(fn($line) => str_replace(["\f"], '', $line))
        ->values();
    }
}
