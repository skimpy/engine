<?php

declare(strict_types=1);

namespace Skimpy\File;

use DateTime;
use Symfony\Component\Finder\SplFileInfo;
use Doctrine\Common\Collections\ArrayCollection;

class ContentFile
{
    use PathResolver;

    protected $file;
    protected $content;
    protected $frontMatter;

    public function __construct(
        SplFileInfo $file,
        ?string $content = '',
        FrontMatter $frontMatter
    ) {
        $this->file = $file;
        $this->content = $content;
        $this->frontMatter = $frontMatter;
    }

    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getFrontMatter(): FrontMatter
    {
        return $this->frontMatter;
    }

    /**
     * Returns filename w/o extension
     */
    public function getFilename(): string
    {
        $exclude = '.'.$this->file->getExtension();

        return $this->file->getBasename($exclude);
    }

    public function getTitle(): string
    {
        if ($this->frontMatter->has('title')) {
            return $this->frontMatter->getTitle();
        }

        return $this->getTitleizedSlug();
    }

    public function getTitleizedSlug(): string
    {
        $words = array_map('ucfirst', explode('-', $this->getSlug()));

        return implode(' ', $words);
    }

    /**
     * Front matter date or filemtime
     */
    public function getDate(): DateTime
    {
        if (false === $this->frontMatter->has('date')) {
            # Defaults to last modified
            return (new DateTime)->setTimestamp($this->file->getMTime());
        }

        $date = $this->frontMatter->getDate();

        if ($date instanceof DateTime) {
            return $date;
        }

        return (new DateTime)->setTimestamp($date);
    }

    /**
     * Front matter SEO Meta Description or null
     */
    public function getDescription(): ?string
    {
        return $this->frontMatter->getDescription();
    }

    public function getExcerpt(): ?string
    {
        return $this->frontMatter->getExcerpt();
    }

    public function getSeoTitle(): ?string
    {
        return $this->frontMatter->getSeoTitle();
    }

    public function getTerms(): ArrayCollection
    {
        return $this->frontMatter->getTerms();
    }

    public function getTaxonomies(): ArrayCollection
    {
        return $this->frontMatter->getTaxonomies();
    }

    public function getMetadata(): array
    {
        return $this->frontMatter->getMetadata();
    }

    /**
     * URI to the content based on directory path
     *
     # Directory       | URI
     * ------------------------------------
     * foo/bar/baz.md  | foo/bar/baz
     *
     * Files named index.md do not use the filename in the URI:
     * index.md         => /
     * foo/index.md     => foo/
     * foo/bar/index.md => foo/bar/index
     *
     * @return string
     */
    public function getUri(): string
    {
        if ($this->isIndex()) {
            return $this->getUriPath();
        }

        $path = $this->getUriPath().DIRECTORY_SEPARATOR.$this->getFilename();

        return trim($path, DIRECTORY_SEPARATOR);
    }

    public function getTemplate(): ?string
    {
        return $this->frontMatter->getTemplate();
    }

    public function getType(): string
    {
        if ($this->isTopLevel()) {
            return 'entry';
        }

        return $this->getUppermostParentDir();
    }

    /**
     * Returns true if the file is not top level and is named index.
     */
    public function isIndex(): bool
    {
        if ($this->isTopLevel()) {
            return false;
        }

        return 'index' === $this->getFilename();
    }

    protected function getSlug(): string
    {
        return basename($this->getUri());
    }

    /**
     * Returns true if the file lives directly below the content dir.
     */
    protected function isTopLevel(): bool
    {
        return 'content' === $this->getUppermostParentDir();
    }

    /**
     * Returns the URI parts to the Entry up to but not including
     * the slug, based on the file location.
     */
    protected function getUriPath(): string
    {
        $path = dirname($this->getPathFromContent());
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        $uriParts = array_filter($parts, function ($dirname) {
            return false === $this->isTopLevel();
        });

        return trim(implode(DIRECTORY_SEPARATOR, $uriParts), DIRECTORY_SEPARATOR);
    }
}
