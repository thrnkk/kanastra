<?php

namespace App\Providers;

use App\Services\FileProcessService;
use Illuminate\Support\ServiceProvider;

class FileProcessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(
            FileProcessService::class
        );
    }
}
