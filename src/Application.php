<?php

declare(strict_types=1);

namespace Skimpy;

class Application extends \Laravel\Lumen\Application
{
    public function getConfigurationPath($name = null)
    {
        $dir = dirname(__DIR__).'/config/';

        if (! $name) {
            return $dir;
        }

        return $dir.$name.'.php';
    }
}