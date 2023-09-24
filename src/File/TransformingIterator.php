<?php

declare(strict_types=1);

namespace Skimpy\File;

use Iterator;
use SplFileInfo;
use IteratorIterator;
use Skimpy\Symfony\FinderFactory;
use Skimpy\Symfony\FinderFactoryInterface;

/**
 * TransformingIterator
 *
 * Creates an iterator from files located at $path.
 * And runs transform on them with transformer supplied
 * in the constructor.
 *
 * Override IteratorIterator::getCurrent and return
 * the transformed File. The current iteration
 * will always be the transformed version of the file.
 */
class TransformingIterator extends IteratorIterator
{
    protected $path;
    protected $transformer;

    /**
     * The extensions to include when finding files
     */
    protected $extensions = [];

    protected $finderFactory;

    /**
     * Cache the iterator so we don't have to find the files every time.
     */
    protected $iterator;

    public function __construct(
        string $path,
        $transformer,
        array $extensions = [],
        FinderFactoryInterface $finderFactory = null
    ) {
        $this->path = $path;
        $this->transformer = $transformer;
        $this->extensions = $extensions;
        $this->finderFactory = $finderFactory ?: new FinderFactory;

        parent::__construct($this->getIterator());
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    public function current(?SplFileInfo $file = null): mixed
    {
        $splFileInfo = $file ?: parent::current();

        return $this->transformer->transform($splFileInfo);
    }

    public function getIterator(): Iterator
    {
        if (is_null($this->iterator)) {
            $finder = $this->finderFactory->createFinder();
            $finder->files()->in($this->path);

            if (false === empty($this->extensions)) {
                $finder->name($this->getExtensionsRegex());
            }

            $this->iterator = $finder->getIterator();
        }

        return $this->iterator;
    }

    /**
     * Returns regex used to filter files
     *
     * Example: '(\.md$|\.markdown|\.mdown$)'
     */
    protected function getExtensionsRegex(): string
    {
        $regex = '';
        $last = count($this->extensions) - 1;
        foreach ($this->extensions as $index => $ext) {
            $regex .= '\.'.$ext.'$';
            $regex .= $index !== $last ? '|' : '';
        }

        return '('.$regex.')';
    }
}
