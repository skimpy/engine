<?php

declare(strict_types=1);

namespace Skimpy\Contracts;

use Symfony\Component\Finder\SplFileInfo;

interface FileTransformer
{
    public function transform(SplFileInfo $fileInfo): Entity;
}
