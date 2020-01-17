<?php

declare(strict_types=1);

namespace Skimpy\Symfony;

use Symfony\Component\Finder\Finder;

interface FinderFactoryInterface
{
    public function createFinder(): Finder;
}
