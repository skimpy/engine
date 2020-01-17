<?php

declare(strict_types=1);

namespace Skimpy\CMS;

use Skimpy\Repo\Terms;
use Skimpy\Repo\Entries;
use Skimpy\Repo\Taxonomies;
use Skimpy\Contracts\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * Converts URIs to Entities
 */
class EntityResolver
{
    protected $entries;
    protected $taxonomies;
    protected $terms;

    public function __construct(
        Entries $entries,
        Taxonomies $taxonomies,
        Terms $terms
    ) {
        $this->entries = $entries;
        $this->taxonomies = $taxonomies;
        $this->terms = $terms;
    }

    /**
     * Takes a URI (blog/my-blog-post) and returns an Entity
     */
    public function resolve(string $path): ?Entity
    {
        foreach ($this->getRepositories() as $repo) {
            $entity = $this->$repo->findOneBy(['uri' => $path]);

            if (is_null($entity)) {
                continue;
            }

            if ($entity instanceof Taxonomy && false === $entity->hasPublicTermsRoute()) {
                continue;
            }

            return $entity;
        }

        return null;
    }

    public function getRepository(Entity $entity): EntityRepository
    {
        $repo = $this->getRepositories()[get_class($entity)];

        return $this->$repo;
    }

    protected function getRepositories(): array
    {
        return [
            ContentItem::class => 'entries',
            Taxonomy::class    => 'taxonomies',
            Term::class        => 'terms',
        ];
    }
}
