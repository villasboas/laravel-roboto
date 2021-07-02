<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Services\WebService;

class LoginAutomationJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new Filesystem)->cleanDirectory(public_path('screenshots'));

        $webService = WebService::make()->open();

        $webService->navigate("https://login.live.com/")->takeScreenshot();

        $webService->wait(5)->findElement('id', 'i0116')
            ->input('desafiotks@outlook.com');
        $webService->findElement('id', 'idSIButton9')
            ->click()
            ->takeScreenshot();

        $webService->findElement('id', 'i0118')->input('TKS13Desafio');
        $webService->findElement('id', 'idSIButton9')->click()->wait(5);

        $webService->navigate("https://outlook.live.com/mail/0/inbox")
            ->takeScreenshot();
    }
}
