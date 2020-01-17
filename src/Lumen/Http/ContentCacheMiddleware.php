<?php

namespace Skimpy\Lumen\Http;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerInterface;
use Illuminate\Contracts\Config\Repository;
use Skimpy\Http\Middleware\ContentCacheHandler;

class ContentCacheMiddleware
{
    private $handler;

    public function __construct(ContentCacheHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->handler->handleRequest($request, $this->getConfig());

        return $next($request);
    }

    public function getConfig(): ContainerInterface
    {
        return new class(config()) implements ContainerInterface
        {
            private $config;

            public function __construct(Repository $config)
            {
                $this->config = $config;
            }

            public function get($id)
            {
                return $this->config->get($id);
            }

            public function has($id)
            {
                return $this->config->has($id);
            }
        };
    }
}
