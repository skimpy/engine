<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use Skimpy\CMS\ContentItem;
use Skimpy\Contracts\Entity;
use Skimpy\File\ContentFile;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Transforms a File into a ContentItem
 *
 * First it transforms the File into a ContentFile object,
 * then it transforms the ContentFile object into a ContentItem.
 */
class FileToContentItem
{
    protected $fileToContentFile;

    public function __construct(FileToContentFile $fileToContentFile)
    {
        $this->fileToContentFile = $fileToContentFile;
    }

    public function transform(SplFileInfo $file): Entity
    {
        try {
            $contentFile = $this->fileToContentFile->transform($file);
            $contentItem = $this->buildContentItem($contentFile);

            return $contentItem;
        } catch (\Exception $e) {
            throw new TransformationFailure($e->getMessage(), 0, $e, $file->getRealPath());
        }
    }

    protected function buildContentItem(ContentFile $contentFile): ContentItem
    {
        $data = [
            'uri'         => $contentFile->getUri(),
            'title'       => $contentFile->getTitle(),
            'date'        => $contentFile->getDate(),
            'type'        => $contentFile->getType(),
            'content'     => $contentFile->getContent(),
            'template'    => $contentFile->getTemplate(),
            'description' => $contentFile->getDescription(),
            'seoTitle'    => $contentFile->getSeoTitle(),
            'excerpt'     => $contentFile->getExcerpt(),
            'isIndex'     => $contentFile->isIndex(),
        ];

        $contentItem = ContentItem::fromArray($data);

        foreach ($contentFile->getMetadata() as $k => $v) {
            $contentItem->setMeta($k, $v);
        }

        foreach ($contentFile->getTerms() as $term) {
            $contentItem->addTerm($term);
        }

        return $contentItem;
    }
}
