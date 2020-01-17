<?php

declare(strict_types=1);

namespace Skimpy;

use Skimpy\CMS\Term;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\ContentItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class is just a wrapper around the repositories.
 * It only exists to simplify the API.
 */
class Skimpy
{
    protected $contentRepository;
    protected $taxonomyRepository;
    protected $termRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->contentRepository = $em->getRepository(ContentItem::class);
        $this->taxonomyRepository = $em->getRepository(Taxonomy::class);
        $this->termRepository = $em->getRepository(Term::class);
    }

    public function findBy(
        array $criteria,
        array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array
    {
        return $this->contentRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param string $taxonomySlug category, tag, product-type, etc
     * @param string $termSlug     web-development, unix, etc
     */
    public function getArchive(string $taxonomySlug, string $termSlug): array
    {
        $taxonomy = $this->taxonomyRepository->findOneBy(['uri' => $taxonomySlug]);
        $term = $this->termRepository->findOneBy(['taxonomy' => $taxonomy, 'slug' => $termSlug]);

        return [
            'taxonomy' => $taxonomy,
            'term'     => $term,
            'items'    => $term->getEntries()->toArray(),
        ];
    }

    /**
     * @param string $taxonomySlug 'category'
     * @param string $termSlug     'unix'
     */
    protected function getTerm(string $taxonomySlug, string $termSlug): ?Term
    {
        $taxonomy = $this->taxonomyRepository->findOneBy(['uri' => $taxonomySlug]);

        return $this->termRepository->findOneBy(
            [
                'taxonomy' => $taxonomy,
                'slug'     => $termSlug,
            ]
        );
    }
}
