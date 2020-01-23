<?php

declare(strict_types=1);

namespace Skimpy\CMS;

use Skimpy\Contracts\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Skimpy\Repo\Terms")
 * @ORM\Table(name="term")
 */
class Term implements Entity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Unique URI to the Term
     *
     * The taxonomy URI appended to the term slug.
     *
     * @ORM\Column(type="string")
     */
    protected $uri;

    /**
     * @ORM\ManyToOne(targetEntity="Skimpy\CMS\Taxonomy", inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\Column(type="string", name="term_name")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Skimpy\CMS\ContentItem", mappedBy="terms")
     *
     * @var ArrayCollection|ContentItem[] Items which have this term
     */
    protected $contentItems;

    public static function fromArray(array $data)
    {
        return new static($data['taxonomy'], $data['name'], $data['slug']);
    }

    public function __construct(Taxonomy $taxonomy, string $name, string $slug)
    {
        $this->contentItems = new ArrayCollection;

        $this->setName($name);
        $this->setSlug($slug);
        $this->uri = $taxonomy->getUri().'/'.$this->getSlug();

        $this->taxonomy = $taxonomy;
        $this->taxonomy->addTerm($this);
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

    public function getTaxonomy(): Taxonomy
    {
        return $this->taxonomy;
    }

    public function isTaxonomy(Taxonomy $taxonomy): bool
    {
        return $this->taxonomy->getId() == $taxonomy->getId();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Returns all entries with this term
     *
     * @return ArrayCollection|PersistentCollection
     */
    public function getEntries()
    {
        return $this->contentItems;
    }

    public function addContentItem(ContentItem $item)
    {
        $this->contentItems->add($item);

        return $this;
    }

    public function removeContentItem(ContentItem $item)
    {
        $this->contentItems->removeElement($item);

        return $this;
    }

    public function getEntityName(): string
    {
        return 'term';
    }

    public function hasTemplate(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return $this->getKey();
    }

    protected function setName(string $name): void
    {
        $this->name = $name;
    }

    protected function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
