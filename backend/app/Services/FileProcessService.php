<?php

namespace App\Services;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FileProcessService
{
    public function __construct(private readonly File $files)
    {
    }

    /**
     * @param int $userId
     * @return User
     * @throws ModelNotFoundException
     */
    public function process(string $filePath, string $originalFileName)
    {
        $zipFile = 'zip://' . storage_path('app/public/' . $filePath) . "#{$originalFileName}";

        $line = 0;
        $skipHeader = true;
        $startingTime = microtime(true);
        $fileStream = fopen($zipFile, 'r');

        File::updateOrCreate(
            ['name' => basename($filePath)],
            ['status' => 0, 'rows' => 0, 'execution' => 0, 'original_name' => $originalFileName]
        );

        while ($row = fgetcsv($fileStream)) {
            if ($skipHeader) {
                $skipHeader = false;
                continue;
            }


            $line += 1;
        }

        $endTime = microtime(true);
        $executionTime = ($endTime - $startingTime) * 1000;

        File::updateOrCreate(
            ['name' => basename($filePath)],
            ['status' => 2, 'rows' => $line, 'execution' => $executionTime]
        );


        fclose($fileStream);
        unlink(storage_path('app/public/' . $filePath));
    }
}
