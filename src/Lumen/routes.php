<?php

use Illuminate\Http\Request;
use Skimpy\Http\Controller\GetController;

$router->get(app('skimpy.uri_prefix'), ['middleware' => ['skimpy.cache'], function () {
    $skimpy = app('skimpy');

    $entries = $skimpy->findBy(['type' => 'entry'], ['date' => 'DESC'], config('skimpy.entries_on_home_page', 5));

    $data = [
        'seotitle' => 'Home',
        'entries'  => $entries
    ];

    return view('home', $data);
}]);

$router->get(app('skimpy.uri_prefix') . '{uri:.+}', ['middleware' => ['skimpy.cache'], function ($uri, Request $request) {

    $controller = app(GetController::class);

    return $controller->handle($request);
}]);
