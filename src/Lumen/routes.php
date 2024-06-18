<?php

use Illuminate\Http\Request;
use Skimpy\Http\Controller\GetController;

(function($router) {
    $withEntries = config('skimpy.site.entries');

    foreach ($withEntries as $index => $config) {

        $path = rtrim(app('skimpy.uri_prefix'), '/') . '/' . ltrim($index, '/');

        $router->get($path, ['middleware' => ['skimpy.cache'], function () use ($config) {

            $entries = app('skimpy')->findBy(
                ['type' => 'entry'],
                ['date' => 'DESC'],
                $config['limit']
            );

            return view($config['template'], [
                'seotitle' => $config['seotitle'],
                'entries' => $entries,
            ]);
        }]);
    }
})($router);

$router->get(app('skimpy.uri_prefix') . '{uri:.+}', ['middleware' => ['skimpy.cache'], function ($uri, Request $request) {

    $controller = app(GetController::class);

    return $controller->handle($request);
}]);
