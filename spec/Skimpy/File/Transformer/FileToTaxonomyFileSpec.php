<?php namespace spec\Skimpy\File\Transformer;

use Skimpy\File\TaxonomyFile;
use spec\Skimpy\ObjectBehavior;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Finder\SplFileInfo;
use Skimpy\File\Transformer\TransformationFailure;

class FileToTaxonomyFileSpec extends ObjectBehavior
{
    function let(Parser $parser)
    {
        $this->beConstructedWith($parser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\Transformer\FileToTaxonomyFile');
    }

    function it_transforms_a_file_into_a_taxonomy_file(
        SplFileInfo $file,
        Parser $parser
    ) {
        $data = [
            'name' => 'Category',
            'plural_name' => 'Categories',
            'terms' => [
                [
                    'name' => 'Web Development',
                    'slug' => 'web-development',
                ],
                [
                    'name' => 'foo BAR baz',
                    'slug' => 'foo-bar-baz'
                ]
            ]
        ];

        $contents = file_get_contents($this->getTaxonomyDir().'/categories.yaml');
        $file->getContents()->willReturn($contents);
        $parser->parse($contents)->willReturn($data);

        $file->getFilename()->willReturn('categories.yaml');
        $file->getExtension()->willReturn('yaml');

        $config = [
            'has_public_terms_route' => true,
        ];

        $taxonomyFile = new TaxonomyFile(
            'categories',
            $data['name'],
            $data['plural_name'],
            $data['terms'],
            $config
        );

        $this->transform($file)->shouldBeLike($taxonomyFile);
    }

    function it_throws_a_transformation_failure_when_transform_fails(
        SplFileInfo $file,
        Parser $parser
    ) {
        $contents = "
        plural_name: categories
        terms:
          -
            name: Web Development
            slug: web-development
        ";

        $data = [
            'plural_name' => 'Categories',
            'terms' => [['name' => 'Web Development', 'slug' => 'web-development']]
        ];

        $file->getContents()->willReturn($contents);
        $parser->parse($contents)->willReturn($data);
        $file->getRealPath()->willReturn('/path/to/categories.yaml');

        $failure = new TransformationFailure(
            'Missing required fields (name) in taxonomy file: /path/to/categories.yaml'
        );

        $this->shouldThrow($failure)->during('transform', [$file]);
    }
}
