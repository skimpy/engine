<?php

declare(strict_types=1);

namespace Skimpy\File;

use Skimpy\Symfony\FinderFactoryInterface;
use Skimpy\File\Transformer\FileToTaxonomy;

class TaxonomyIterator extends TransformingIterator
{
    const EXTENSIONS = ['yml', 'yaml'];

    protected $iterator;

    public function __construct(
        string $path,
        FileToTaxonomy $transformer,
        FinderFactoryInterface $finderFactory = null
    ) {
        parent::__construct($path, $transformer, static::EXTENSIONS, $finderFactory);
    }
}
