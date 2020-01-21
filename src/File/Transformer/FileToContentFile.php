<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use Michelf\Markdown;
use Skimpy\File\ContentFile;
use Skimpy\File\FrontMatter;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Finder\SplFileInfo;

class FileToContentFile
{
    const CONTENT = 1;
    const FRONT_MATTER = 0;

    protected $transformer;
    protected $markdown;
    protected $parser;

    public function __construct(
        ArrayToFrontMatter $transformer,
        Markdown $markdown = null,
        Parser $parser = null
    ) {
        $this->transformer = $transformer;
        $this->markdown = $markdown ?: new Markdown;
        $this->parser = $parser ?: new Parser;
    }

    public function transform(SplFileInfo $file): ContentFile
    {
        $fileContents = $file->getContents();

        if (false === $this->hasFrontMatter($fileContents)) {
            $html = $this->markdown->transform($fileContents);

            return new ContentFile($file, $html, new FrontMatter);
        }

        $html = $this->getHtml($fileContents);
        $frontMatter = $this->transformer->transform($this->getFrontMatterData($fileContents));

        $contentFile = new ContentFile($file, $html, $frontMatter);

        $contentFile->setType($frontMatter->getType());

        return $contentFile;
    }

    public function getHtml(string $content): string
    {
        return $this->markdown->transform(
            $this->getContentPart($content, static::CONTENT)
        );
    }

    public function getFrontMatterData(string $content): array
    {
        return (array) $this->parser->parse(
            $this->getContentPart($content, static::FRONT_MATTER)
        );
    }

    protected function getContentPart(string $content, int $part): string
    {
        return array_map(
            'trim',
            explode(FrontMatter::SEPARATOR, $content, 2)
        )[$part];
    }

    protected function hasFrontMatter(string $content): bool
    {
        return false !== strpos($content, PHP_EOL.FrontMatter::SEPARATOR.PHP_EOL);
    }
}
