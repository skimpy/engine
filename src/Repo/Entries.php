<?php

declare(strict_types=1);

namespace Skimpy\Repo;

use Skimpy\CMS\ContentItem;

class Entries extends BaseRepo
{
    /**
     * Returns all entries in a subdirectory of Entry
     *
     * Allows for recursive index functionality
     */
    public function getIndexEntries(ContentItem $entry): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM Skimpy\CMS\ContentItem e WHERE e.uri LIKE :uri AND e.isIndex = 0')
            ->setParameter('uri', $entry->getUri().'%')
            ->getResult()
        ;
    }
}
