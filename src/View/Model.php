<?php

declare(strict_types=1);

namespace Skimpy\View;

use Skimpy\Application;

class Model
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function url(?string $uri = ''): string
    {
        return str_replace('//', '/', $this->getUri($uri));
    }

    private function getUri(string $uri = ''): string
    {
        $prefix = $this->app->get('skimpy.uri_prefix');

        if (empty($prefix) && empty($uri)) return '/';
        if (empty($uri)) return rtrim($prefix, '/');
        if ('' == $prefix) return '/' . trim($uri, '/');

        return '/' . trim($prefix, '/') . '/' . trim($uri, '/');
    }

    /**
     * Allows for accessing skimpy config in views using {{ skimpy.site.{configKey} }}
     */
    public function site(): object
    {
        return (object) config('skimpy.site');
    }

    /**
     * Allows for calling methods in twig with parentheses
     */
    public function __get($method)
    {
        try {
            $this->$method();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}