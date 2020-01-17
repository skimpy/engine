<?php namespace spec\Skimpy\File\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\File\Transformer\FileToTaxonomyFile;
use Skimpy\File\TaxonomyFile;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\Term;
use Symfony\Component\Finder\SplFileInfo;
use Skimpy\File\Transformer\TransformationFailure;

class FileToTaxonomySpec extends ObjectBehavior
{
    function let(FileToTaxonomyFile $transformer)
    {
        $this->beConstructedWith($transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\Transformer\FileToTaxonomy');
    }

    function it_transforms_a_file_into_a_taxonomy(
        FileToTaxonomyFile $transformer,
        SplFileInfo $file,
        TaxonomyFile $taxonomyFile,
        Term $term
    ) {
        $transformer->transform($file)
            ->willReturn($taxonomyFile)
        ;

        $taxonomyFile->getName()->willReturn('Category');
        $taxonomyFile->getPluralName()->willReturn('Categories');
        $taxonomyFile->getSlug()->willReturn('categories');

        $termData = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Foo', 'slug' => 'foo']
        ];
        $taxonomyFile->getTerms()->willReturn($termData);

        $this->transform($file)->shouldReturnAnInstanceOf(Taxonomy::class);
    }

    function it_throws_a_tranformation_failure_when_tranform_fails(
        FileToTaxonomyFile $transformer,
        SplFileInfo $file
    ) {
        $transformer->transform($file)->willThrow(new \Exception);
        $this->shouldThrow(new TransformationFailure)->duringTransform($file);
    }
}
