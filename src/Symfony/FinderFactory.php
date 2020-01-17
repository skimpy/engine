<?php

declare(strict_types=1);

namespace Skimpy\Symfony;

use Symfony\Component\Finder\Finder;

class FinderFactory implements FinderFactoryInterface
{
    public function createFinder(): Finder
    {
        return new Finder;
    }
}
