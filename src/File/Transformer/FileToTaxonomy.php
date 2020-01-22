<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use Skimpy\CMS\Term;
use Skimpy\CMS\Taxonomy;
use Skimpy\File\TaxonomyFile;
use Symfony\Component\Finder\SplFileInfo;

/**
 * FileToTaxonomy
 *
 * Transforms a file into into a Taxonomy object.
 * First it transforms the file into a TaxonomyFile,
 * then it transforms the TaxonomyFile into a Taxonomy.
 */
class FileToTaxonomy
{
    protected $fileToTaxonomyFile;

    public function __construct(FileToTaxonomyFile $fileToTaxonomyFile)
    {
        $this->fileToTaxonomyFile = $fileToTaxonomyFile;
    }

    public function transform(SplFileInfo $file): Taxonomy
    {
        try {
            $taxonomyFile = $this->fileToTaxonomyFile->transform($file);
            $taxonomy = $this->buildTaxonomy($taxonomyFile);

            return $taxonomy;
        } catch (\Exception $e) {
            throw new TransformationFailure($e->getMessage(), 0, $e, $file->getRealPath());
        }
    }

    protected function buildTaxonomy(TaxonomyFile $taxonomyFile): Taxonomy
    {
        $tax = new Taxonomy(
            $taxonomyFile->getName(),
            $taxonomyFile->getPluralName(),
            $taxonomyFile->getSlug(),
            $taxonomyFile->getConfig()
        );

        foreach ($taxonomyFile->getTerms() as $data) {
            # Don't add term to Taxonomy here,
            # it's done in constructor of Term.
            new Term($tax, $data['name'], $data['slug']);
        }

        return $tax;
    }
}
