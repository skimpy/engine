<?php

declare(strict_types=1);

namespace Skimpy\File;

use DateTime;
use Skimpy\CMS\Term;
use Skimpy\CMS\Metadata;
use Skimpy\CMS\Taxonomy;
use Doctrine\Common\Collections\ArrayCollection;

class FrontMatter
{
    /*
     * Miscellaneous key values provided in the front matter
     *
     * Any key that is not a configurable property and is
     * not a taxonomy qualifies as metadata.
     */
    use Metadata;

    const SEPARATOR = '---';

    /*
     * Configurable keys meet the following criteria:
     * - Are actual properties of a ContentItem
     * - Can be overridden/set via front matter
     * - Are not associations between entities
     */
    const CONFIGURABLE_KEYS = 'title|seoTitle|date|description|excerpt|template';

    protected $title;
    protected $seoTitle;
    protected $date;
    protected $description;
    protected $excerpt;
    protected $template;
    protected $terms;

    public function __construct()
    {
        $this->terms = new ArrayCollection;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Taxonomy[]|ArrayCollection
     */
    public function getTaxonomies(): ArrayCollection
    {
        $taxonomies = array_unique(
            array_map(function (Term $term) {
                return $term->getTaxonomy();
            }, $this->terms->toArray())
        );

        return new ArrayCollection($taxonomies);
    }

    public function hasTaxonomies(): bool
    {
        return false === $this->getTaxonomies()->isEmpty();
    }

    public function addTerm(Term $term): self
    {
        if (false === $this->terms->contains($term)) {
            $this->terms->add($term);
        }

        return $this;
    }

    public function addTerms(array $terms): self
    {
        foreach ($terms as $term) {
            $this->addTerm($term);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Term[]
     */
    public function getTerms(): ArrayCollection
    {
        return $this->terms;
    }

    public function getConfigurableKeys(): array
    {
        return explode('|', static::CONFIGURABLE_KEYS);
    }

    public function has(string $prop): bool
    {
        if (false === property_exists($this, $prop)) {
            throw new \RuntimeException(
                'Incorrect usage of FrontMatter::has($prop). '.
                "FrontMatter has no property $prop to confirm is not null."
            );
        }

        $method = 'get'.ucfirst($prop);

        return false === is_null($this->$method());
    }
}
