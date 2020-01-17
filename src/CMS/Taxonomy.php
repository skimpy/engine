<?php

declare(strict_types=1);

namespace Skimpy\CMS;

use Skimpy\Contracts\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="Skimpy\Repo\Taxonomies")
 * @ORM\Table(
 *     name="taxonomy",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="uri_uq", columns={"uri"}
 *         )
 *     }
 * )
 */
class Taxonomy implements \IteratorAggregate, \Countable, Entity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The URI to the taxonomy. (cateogories, tags)
     *
     * The URI is also used to identify the taxonomy in the
     * ContentFile FrontMatter and associate terms to the ContentItem.
     * Examples:
     *     categories: [foo, bar, baz]
     *     products/categories: [Top Rated, On Sale]
     *
     * @ORM\Column(type="string")
     */
    protected $uri;

    /**
     * @ORM\Column(type="string", name="tax_name")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $pluralName;

    /**
     * @ORM\OneToMany(targetEntity="Skimpy\CMS\Term", mappedBy="taxonomy", cascade={"persist"})
     *
     * @var ArrayCollection|Term[]
     */
    protected $terms;

    /**
     * @ORM\Column(type="array")
     */
    protected $config = [];

    public static function fromArray(array $data): self
    {
        $config = isset($data['config']) ? $data['config'] : [];

        return new static($data['name'], $data['pluralName'], $data['uri'], $config);
    }

    public function __construct(string $name, string $pluralName, string $uri, ?array $config = [])
    {
        $this->name = $name;
        $this->pluralName = $pluralName;
        $this->uri = $uri;
        $this->config = $config;

        $this->terms = new ArrayCollection;
    }

    public function getKey(): string
    {
        return $this->getUri();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPluralName(): string
    {
        return $this->pluralName;
    }

    /**
     * @return ArrayCollection|PeristentCollection|Term[]
     */
    public function getTerms()
    {
        return $this->terms;
    }

    public function addTerm(Term $term): self
    {
        if (false === $this->hasTerm($term)) {
            $this->terms->add($term);
        }

        return $this;
    }

    public function removeTerm(Term $term): self
    {
        $this->terms->removeElement($term);

        return $this;
    }

    /**
     * @return array|ContentItem[]
     */
    public function getEntries(): array
    {
        $entries = [];

        foreach ($this->getTerms() as $term) {
            foreach ($term->getEntries() as $entry) {
                if (in_array($entry, $entries)) continue;
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    public function hasTerm(Term $term): bool
    {
        return $this->terms->contains($term);
    }

    public function getTermByName(string $termName): ?Term
    {
        $term = $this->getTermsBy('name', $termName);

        return $term->first() ? $term->first() : null;
    }

    public function getTermBySlug(string $termSlug): ?Term
    {
        $term = $this->getTermsBy('slug', $termSlug);

        return $term->first() ? $term->first() : null;
    }

    protected function getTermsBy(string $prop, $value): ArrayCollection
    {
        $criteria = new Criteria(Criteria::expr()->eq($prop, $value));

        return $this->terms->matching($criteria);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Returns true if the terms should be accessible over http
     *
     * Defaults to true
     *
     * Example taxonomy route: /categories
     * Example taxonomy term route: /categories/web-development
     */
    public function hasPublicTermsRoute(): bool
    {
        return $this->config['has_public_terms_route'] ?? true;
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    /**
     * @return ArrayCollection|PersistentCollection|Term[]
     */
    public function getIterator()
    {
        return $this->getTerms();
    }

    /**
     * This allows use of special twig vars inside of loops.
     * Since this is iterable, we can foreach it and
     * loop through it's terms.
     */
    public function count(): int
    {
        return $this->getTerms()->count();
    }

    public function getEntityName(): string
    {
        return 'taxonomy';
    }

    public function hasTemplate(): bool
    {
        return false;
    }
}
