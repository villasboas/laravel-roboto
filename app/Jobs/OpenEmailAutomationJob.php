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

class OpenEmailAutomationJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $subject)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $webService = WebService::make()->open()->wait(2)->takeScreenshot();

        $webService->findElement('xpath', "//input[@aria-label='Search']")
            ->click()
            ->input($this->subject)
            ->takeScreenshot();

        $webService->findElement('xpath', "//span[contains(text(),'$this->subject')]")
            ->click()
            ->takeScreenshot();
    }
}
