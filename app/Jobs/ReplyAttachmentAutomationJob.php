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

class ReplyAttachmentAutomationJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $attachment)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $webService = WebService::make()->open();

        $webService->wait(10)
        ->takeScreenshot()
        ->findElement('xpath', "//div[@id='ReadingPaneContainerId']/div/div/div/div[2]/div/div/div/div/div/div/div[3]/div[2]/div/div/div/div/div/div/div/div/div/button/span/i")
        ->click()
        ->wait(2)
        ->takeScreenshot();

        $webService->findElement('name', "Attach")
        ->click()
        ->takeScreenshot()
        ->wait(1);

        $webService->findElement('name', 'Browse this computer')
        ->click()
        ->takeScreenshot();

        $webService->findElement(
            'xpath', "//div[@id='ReadingPaneContainerId']/div/div/div/div[2]/div/div/div[2]/div/div/div[3]/input[2]"
        )
        ->input($this->attachment)
        ->wait(20)
        ->takeScreenshot();

        $webService->findElement(
            'xpath', "//button[@title='Send (Ctrl+Enter)']"
        )
        ->click()
        ->takeScreenshot();
    }
}
