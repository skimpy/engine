<?php

declare(strict_types=1);

namespace Skimpy\Database;

use Skimpy\File\ContentIterator;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;

class Populator
{
    protected $em;
    protected $contentIterator;
    protected $taxonomies;
    protected $schemaTool;

    public function __construct(
        EntityManagerInterface $em,
        ContentIterator $contentIterator,
        array $taxonomies,
        SchemaTool $schemaTool = null
    ) {
        $this->em = $em;
        $this->contentIterator = $contentIterator;
        $this->taxonomies = $taxonomies;
        $this->schemaTool = $schemaTool ?: new SchemaTool($em);
    }

    /**
     * Rebuilds and populates the database based on data retrieved
     * from the filesystem.
     */
    public function populate(): void
    {
        $this->rebuildDatabase();
        $this->insertData();
    }

    protected function rebuildDatabase(): void
    {
        $this->schemaTool->dropDatabase();

        $entityMeta = $this->em->getMetadataFactory()->getAllMetadata();

        $this->schemaTool->updateSchema($entityMeta);
    }

    protected function insertData(): void
    {
        foreach ($this->taxonomies as $tax) {
            $this->em->persist($tax);
        }

        foreach ($this->contentIterator->toArray() as $contentItem) {
            $this->em->persist($contentItem);
        }

        $this->em->flush();
    }
}
