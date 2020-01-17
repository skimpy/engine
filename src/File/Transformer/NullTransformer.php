<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use Symfony\Component\Finder\SplFileInfo;

class NullTransformer
{
    public function transform(SplFileInfo $file): SplFileInfo
    {
        return $file;
    }
}
