<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use DateTime;
use Skimpy\CMS\Taxonomy;
use Skimpy\File\FrontMatter;

/**
 * Array To Front Matter Transformer
 *
 * Converts raw data parsed in the 'front matter'
 * of a file into a FrontMatter object.
 */
class ArrayToFrontMatter
{
    protected $registeredTaxonomies;

    public function __construct(array $registeredTaxonomies)
    {
        $this->registeredTaxonomies = $registeredTaxonomies;
    }

    public function transform(array $data): FrontMatter
    {
        $frontMatter = new FrontMatter;

        foreach ($data as $key => $value) {
            if ($taxonomy = $this->getTaxonomyByKey($key)) {
                $terms = $this->getTerms($taxonomy, $value);
                $frontMatter->addTerms($terms);
            }

            if ($isProperty = $this->keyIsFrontMatterProperty($frontMatter, $key)) {
                $setter = 'set'.ucfirst($key);
                if ('date' === $key) {
                    $value = $this->convertToDateTime($value);
                }
                $frontMatter->$setter($value);
            }

            # Metadata is arbitrary data an author wants
            # to have available in the view as a variable.
            $isMetadata = is_null($taxonomy) && false === $isProperty;
            if ($isMetadata) {
                $frontMatter->setMeta($key, $value);
            }
        }

        return $frontMatter;
    }

    protected function getTaxonomyByKey(string $key): ?Taxonomy
    {
        foreach ($this->registeredTaxonomies as $taxonomy) {
            /** @var Taxonomy $taxonomy */
            if ($taxonomy->getKey() === $key) {
                return $taxonomy;
            }
        }

        return null;
    }

    protected function keyIsFrontMatterProperty(FrontMatter $frontMatter, string $key): bool
    {
        $method = 'set'.ucfirst($key);

        return is_callable([$frontMatter, $method]);
    }

    /**
     * YAML dates should be quoted.
     * They must be proper iso8601 if they are not quoted.
     */
    protected function convertToDateTime($date): DateTime
    {
        if ($date instanceof DateTime) return $date;

        if (is_string($date)) return new DateTime($date);

        throw new TransformationFailure('YAML Front Matter dates must be quoted.');
    }

    protected function getTerms(Taxonomy $taxonomy, array $termNames): array
    {
        return array_map(function($name) use ($taxonomy) {
            $term = $taxonomy->getTermByName($name);

            if (is_null($term)) {
                $message = "The Taxonomy '{$taxonomy->getName()}' does not contain term '$name'.";
                throw new TransformationFailure($message);
            }

            return $term;
        }, $termNames);
    }
}
