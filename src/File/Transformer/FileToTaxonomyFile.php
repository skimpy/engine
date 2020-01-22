<?php

declare(strict_types=1);

namespace Skimpy\File\Transformer;

use Skimpy\File\TaxonomyFile;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Transform a file into a TaxonomyFile object.
 *
 * If the taxonomy file doesn't contain required
 * data and appropriate data types, the transformation fails.
 *
 * With a TaxonomyFile object, we can ensure that
 * no attempt is made to create a Taxonomy from
 * an invalid taxonomy file.
 *
 * Only TaxonomyFile objects are used to create
 * Taxonomy objects.
 */
class FileToTaxonomyFile
{
    const REQUIRED_FIELDS = ['name', 'plural_name', 'terms'];

    protected $parser;

    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: new Parser;
    }

    public function transform(SplFileInfo $file): TaxonomyFile
    {
        $data = $this->parser->parse($file->getContents());

        $missingFields = $this->getMissingFields($data);

        if (false === empty($missingFields)) {
            throw new TransformationFailure(sprintf(
                'Missing required fields (%s) in taxonomy file: %s',
                implode(', ', $this->getMissingFields($data)),
                $file->getRealPath()
            ));
        }

        $filename = $file->getFilename();
        $ext = $file->getExtension();
        $slug = explode('.'.$ext, $filename)[0];

        return new TaxonomyFile(
            $slug,
            $data['name'],
            $data['plural_name'],
            $data['terms'],
            $this->getConfig($data)
        );
    }

    protected function getConfig(array $data): array
    {
        return [
            'has_public_terms_route' => $data['has_public_terms_route'] ?? true,
        ];
    }

    protected function getMissingFields(array $data): array
    {
        return array_diff(static::REQUIRED_FIELDS, array_keys($data));
    }
}
