<?php

namespace App\Jobs;

use App\Drivers\FileReader\AccountAnalysisDemonstrativeReader;
use App\Repositories\ProtocolRepository;
use App\Services\FileReaderService;
use App\Services\ItemExportService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractPdfToCsvJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileContent = (new FileReaderService)->setFile(base_path('temp/Dados.pdf'))
        ->loadContent()
        ->applyDriver(new AccountAnalysisDemonstrativeReader)
        ->getContent();

        $protocolRepository = resolve(ProtocolRepository::class);

        foreach ($fileContent['Protocolos'] as $protocol) {
            $protocolData = $fileContent;

            unset($protocolData['Protocolos']);

            $protocolRepository->save(array_merge($protocolData->toArray(), $protocol), $protocol['Numero_Protocolo']);
        }

        resolve(ItemExportService::class)->toCsv();
    }
}
