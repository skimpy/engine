<?php

declare(strict_types=1);

namespace Skimpy\File;

class TaxonomyFile
{
    /**
     * The name of the file without the extension
     *
     * Used as the URI to the taxonomy and to identify
     * a front matter key as this particular taxonomy.
     */
    protected $slug;

    /**
     * Name used to identify the taxonomy to a reader.
     *
     * Example: 'Category'
     */
    protected $name;

    protected $pluralName;
    protected $terms;

    public function __construct(string $slug, string $name, string $pluralName, array $terms)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->pluralName = $pluralName;
        $this->terms = $terms;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPluralName(): string
    {
        return $this->pluralName;
    }

    public function getTerms(): array
    {
        return $this->terms;
    }
}
