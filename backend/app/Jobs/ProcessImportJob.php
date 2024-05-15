<?php

namespace App\Jobs;

use App\Jobs\ChargesImport\ProcessChargeImportJob;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $filePath, private string $originalName)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = storage_path('app/public/' . $this->filePath);

        $mapping = [
            'name' => 0, 'governmentId' => 1, 'email' => 2,
            'debtAmount' => 3, 'debtDueDate' => 4, 'debtID' => 5
        ];

        Log::info($path);

        $fileStream = fopen($path, 'r');
        $skipHeader = true;
        $line = 0;
        $startingTime = microtime(true);

        File::updateOrCreate(
            ['name' => basename($path)],
            ['status' => 0, 'rows' => 0, 'execution' => 0, 'original_name' => $this->originalName]
        );

        while ($row = fgetcsv($fileStream)) {
            if ($skipHeader) {
                $skipHeader = false;
                continue;
            }

            $line += 1;

            dispatch(new ProcessChargeImportJob($row, $mapping))->onQueue('importProcess');
        }

        $endTime = microtime(true);
        $executionTime = ($endTime - $startingTime);

        File::updateOrCreate(
            ['name' => basename($path)],
            ['status' => 2, 'rows' => $line, 'execution' => $executionTime]
        );

        fclose($fileStream);
        unlink($path);
    }
}
