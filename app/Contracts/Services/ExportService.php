<?php

namespace App\Contracts\Services;

interface ExportService
{
    /**
     * Export content to csv
     *
     * @return string
     */
    public function toCsv(): string;
}
