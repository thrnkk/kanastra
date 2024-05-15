<?php

namespace App\Jobs\ChargesImport;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessChargeImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $rowData, private array $mapping)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // TODO: gerar boleto
            // TODO: disparar e-mail
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::info(json_encode($this->rowData));
        }
    }
}
