<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Interfaces Service Provider
 *
 * @package \App\Providers
 */
class InterfacesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->bindFileGeneratorsInterfaces();
        $this->bindOtherInterfaces();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() : void
    {
        //
    }

    /**
     * @return void
     */
    public function bindFileGeneratorsInterfaces() : void
    {
        $this->app->bind(
            \App\Interfaces\FileGenerators\ArchiveGeneratorServiceInterface::class,
            \App\Services\FileGenerators\ZipGeneratorService::class
        );

        $this->app->bind(
            \App\Interfaces\FileGenerators\CsvGeneratorServiceInterface::class,
            \App\Services\FileGenerators\CsvGeneratorService::class
        );

        $this->app->bind(
            \App\Interfaces\FileGenerators\ImageGeneratorServiceInterface::class,
            \App\Services\FileGenerators\SnappyImageGeneratorService::class
        );

        $this->app->bind(
            \App\Interfaces\FileGenerators\PdfGeneratorServiceInterface::class,
            \App\Services\FileGenerators\SnappyPdfGeneratorService::class
        );
    }

    /**
     * @return void
     */
    public function bindOtherInterfaces() : void
    {
        $this->app->bind(
            \App\Interfaces\ShellCommandServiceInterface::class,
            \App\Services\LinuxShellCommandService::class
        );
    }
}
