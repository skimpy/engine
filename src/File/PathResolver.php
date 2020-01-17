<?php

declare(strict_types=1);

namespace Skimpy\File;

use Symfony\Component\Finder\SplFileInfo;

trait PathResolver
{
    abstract public function getFile(): SplFileInfo;

    protected function getUppermostParentDir(): string
    {
        $path = $this->getPathFromContent();
        $pathParts = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));

        if (1 === count($pathParts)) {
            return 'content';
        }

        return $pathParts[0];
    }

    /**
     * Returns the path relative to the content directory
     *
     * path/to/content/foo/bar.md => foo/bar.md
     */
    protected function getPathFromContent(): string
    {
        $pathFromContent = str_replace($this->getPathToContent(), '', $this->getFile()->getRealPath());

        return trim($pathFromContent, DIRECTORY_SEPARATOR);
    }

    /**
     * Detects the path to content directory by examining the path to the file
     *
     * The inner most "content" directory will be considered the
     * actual content directory if multiple directories named "content"
     * exist in the path.
     */
    protected function getPathToContent(): string
    {
        # Reverse the path so we can use the last "content" directory in the
        # path as the actual content directory. This is in case the content
        # folder has a parent folder also called content somewhere down the tree.
        $reverseContentPath = $this->getReverseContentPath();
        $contentPathParts = array_reverse(explode(DIRECTORY_SEPARATOR, $reverseContentPath));
        $pathToContent = trim(implode(DIRECTORY_SEPARATOR, $contentPathParts), DIRECTORY_SEPARATOR);

        return DIRECTORY_SEPARATOR.$pathToContent.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR;
    }

    protected function getReverseContentPath(): string
    {
        $reversePathParts = $this->getReversePathParts();

        if (false === isset($reversePathParts[1])) {
            throw new InvalidContentFileLocation;
        }

        return trim($reversePathParts[1], DIRECTORY_SEPARATOR);
    }

    protected function getReversePathParts(): array
    {
        $path = trim($this->getFile()->getRealPath(), DIRECTORY_SEPARATOR);
        $reversePath = implode(DIRECTORY_SEPARATOR, array_reverse(explode(DIRECTORY_SEPARATOR, $path)));

        return explode('content', $reversePath, 2);
    }
}
