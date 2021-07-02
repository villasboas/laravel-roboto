<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use App\Jobs\OpenEmailAutomationJob;
use App\Jobs\LoginAutomationJob;
use App\Jobs\ExtractPdfToCsvJob;
use App\Services\WebService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Jobs\DownloadAttachmentAutomationJob;
use App\Jobs\ReplyAttachmentAutomationJob;

class RpaDashboard extends Component
{
    protected $loading;

    protected $images;

    protected $batch;

    public $batchId;

    public function start()
    {
        if ($this->batchId) {
            return;
        }

        $batch = Bus::batch([
            new LoginAutomationJob,
            new OpenEmailAutomationJob('[TKS] Teste RPA'),
            new DownloadAttachmentAutomationJob,
            new ExtractPdfToCsvJob,
            new ReplyAttachmentAutomationJob('/home/seluser/Downloads/pdf_to_csv.csv')
        ])
        ->then(function(Batch $batch) {
            WebService::make()->close();
        })
        ->catch(function(Batch $batch) {
            $batch->cancel();
            WebService::make()->close();
        })
        ->finally(function(Batch $batch) {
            WebService::make()->close();
        })->name('batch')->dispatch();

        return redirect("/$batch->id");
    }

    public function loadImages()
    {
        if ($this->batchId) {
            $this->batch = Bus::findBatch($this->batchId);
            if (!$this->batch->finished()) {
                $this->loading = true;
            }
        } else {
            $this->batch = null;
        }
        $this->images = collect(File::files(public_path('screenshots')))
        ->sortByDesc(function ($file) {
            return $file->getFilename();
        });
    }

    public function mount()
    {
        if ($this->batchId) {
            $this->batch = Bus::findBatch($this->batchId);
            if ($this->batch->finished()) {
                return redirect('/');
            } else {
                $this->loading = true;
            }
        } else {
            $this->batch = null;
        }

        $this->images = collect(File::files(public_path('screenshots')))
        ->sortByDesc(function ($file) {
            return $file->getFilename();
        });
    }

    public function render()
    {
        return view('livewire.rpa-dashboard', [
            'images'  => $this->images,
            'loading' => $this->loading
        ]);
    }
}
