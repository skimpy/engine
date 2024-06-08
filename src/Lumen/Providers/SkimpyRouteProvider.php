<?php

declare(strict_types=1);

namespace Skimpy\Lumen\Providers;

use Skimpy\Lumen\Http\ContentCacheMiddleware;

class SkimpyRouteProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $router = $this->app->router;

        $router->group([], function ($router) {
            require __DIR__ . '/../routes.php';
        });

        $this->app->routeMiddleware([
            'skimpy.cache' => ContentCacheMiddleware::class,
        ]);
    }
}
