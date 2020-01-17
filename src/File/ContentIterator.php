<?php

declare(strict_types=1);

namespace Skimpy\File;

use Skimpy\Symfony\FinderFactoryInterface;
use Skimpy\File\Transformer\FileToContentItem;

class ContentIterator extends TransformingIterator
{
    const EXTENSIONS = ['md', 'markdown', 'mdown'];

    protected $iterator;

    public function __construct(
        string $path,
        FileToContentItem $transformer,
        ?FinderFactoryInterface $finderFactory = null
    ) {
        parent::__construct($path, $transformer, static::EXTENSIONS, $finderFactory);
    }
}
