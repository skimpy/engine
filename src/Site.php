<?php

declare(strict_types=1);

namespace Skimpy;

/**
 * Sets up a Skimpy site as a Lumen application.
 */
class Site
{
    private string $publicDir;

    public function __construct(string $publicDir)
    {
        $this->publicDir = $publicDir;
    }

    public function bootstrap(): Application
    {
        (new \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
            dirname($this->publicDir)
        ))->bootstrap();

        $app = new Application(dirname($this->publicDir));

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Skimpy\Lumen\Exceptions\Handler::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \Skimpy\Lumen\Console\Kernel::class
        );

        $app->register('Skimpy\Lumen\Providers\SkimpyServiceProvider');

        return $app;
    }
}