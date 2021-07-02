<?php

namespace App\Jobs;

use App\Services\WebService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadAttachmentAutomationJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        @unlink(base_path('temp/Dados.pdf'));

        $webService = WebService::make()->open();

        $webService->findElement('xpath', "//div[@title='Dados.pdf']")
        ->click()
        ->wait(5)
        ->takeScreenshot();

        $webService->findElement('xpath', "//button[@aria-label='Download']")
            ->click()
            ->wait(20)
            ->takeScreenshot();

        $webService->findElement(
            'xpath', "//button[@title='Close']"
        )->click()
        ->takeScreenshot();
    }
}
