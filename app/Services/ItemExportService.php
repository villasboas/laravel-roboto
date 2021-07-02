<?php

namespace App\Services;

use App\Contracts\Services\ExportService;
use App\Repositories\ItemRepository;

class ItemExportService implements ExportService
{
    /**
     * Exported file name
     *
     * @var string
     */
    protected $fileName;

    /**
     * Constructor method
     *
     * @param ItemRepository $itemRepository
     */
    public function __construct(protected ItemRepository $itemRepository)
    {
        $this->fileName = base_path('temp/pdf_to_csv.csv');
    }

    /**
     * Export items to csv
     *
     * @return string
     */
    public function toCsv(): string
    {
        $items = $this->itemRepository->all();
        @unlink(base_path('temp/pdf_to_csv.csv'));

        $file = fopen($this->fileName, 'w');
        fputcsv($file, array_keys($items[0]));

        foreach ($items as $item) {
            fputcsv($file, array_values($item));
        }

        fclose($file);

        return $this->fileName;
    }
}
