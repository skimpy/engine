<?php

declare(strict_types=1);

namespace Skimpy\Lumen\Providers;

use Illuminate\Http\Request;
use Skimpy\Http\Controller\GetController;
use Skimpy\Lumen\Http\ContentCacheMiddleware;

/**
 * Skimpy handles routing dynamically based on content file paths.
 *
 * Creating a file makes the path to the file a valid route uri
 *
 * Users can make new routes by adding an entry to the `skimpy.site.entries`
 * config array. The entries config array is only used for creating custom archive
 * pages that receieve all entries of type entry. Pages won't show up.
 */
class SkimpyRouteProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->registerUserConfigRoutes();

        $this->routeUrisToEntities();

        /** @var \Laravel\Lumen\Application $app */
        $app = $this->app;

        /**
         * Rebuild the database on every request if enabled via config
         *
         * The database is built based on the site files
         */
        $app->routeMiddleware([
            'skimpy.cache' => ContentCacheMiddleware::class,
        ]);
    }

    /**
     * Everything except "entries" config routes
     * are handled by the GetController.
     *
     * The content file paths are mapped to URIs.
     * The file at the path is converted into an Entity
     * and is available to the view.
     */
    private function routeUrisToEntities(): void
    {
        $this->app->router->get(
            config('skimpy.uri_prefix') . '{uri:.+}',
            [
                'middleware' => ['skimpy.cache'],
                fn (Request $request) => app(GetController::class)->handle($request)
            ]
        );
    }

    /**
     * Register routes from the user's config (skimpy.site.entries)
     *
     * These routes receive an array of entries in the view
     *
     * @example [
     *     '/' => [
     *         'template' => 'home',
     *         'limit' => 3,
     *         'seotitle' => 'Home'
     *         'pinned' => ['post-uri-example', 'other-post-uri']
     *     ],
     *     'articles' => [
     *         'template' => 'articles',
     *         'limit' => null,
     *         'seotitle' => 'Articles'
     *     ],
     * ]
     */
    private function registerUserConfigRoutes(): void
    {
        $withEntries = config('skimpy.site.entries');

        foreach ($withEntries as $uri => $config) {

            $path = rtrim(config('skimpy.uri_prefix'), '/') . '/' . ltrim($uri, '/');

            /** @var \Laravel\Lumen\Routing\Router $router */
            $router = $this->app->router;

            $callback = $this->registerUserConfigRoute($config);

            $router->get($path, ['middleware' => ['skimpy.cache'], $callback]);
        }
    }

    private function registerUserConfigRoute(array $config): \Closure
    {
        return function () use ($config) {
            $params = ['type' => 'entry'];

            if (!empty($config['pinned'])) {
                $params['uri'] = $config['pinned'];
            }

            $entries = app('skimpy')->findBy(
                $params,
                ['date' => 'DESC'],
                $config['limit']
            );

            return view($config['template'], [
                'seotitle' => $config['seotitle'],
                'entries' => $entries,
            ]);
        };
    }
}
