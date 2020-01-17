<?php

namespace spec\Skimpy\File\Transformer;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\Term;
use Skimpy\File\FrontMatter;
use Skimpy\File\Transformer\TransformationFailure;

class ArrayToFrontMatterSpec extends ObjectBehavior
{
    function let()
    {
        $category = $this->getCategoryTaxonomy();
        new Term($category, 'Unix', 'unix');

        $tag = $this->getTagTaxonomy();
        new Term($tag, 'Tag 1', 'tag-1');

        $this->beConstructedWith([$category, $tag]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\Transformer\ArrayToFrontMatter');
    }

    protected function getData()
    {
        return [
            # Properties
            'title'       => 'The Title',
            'date'        => new DateTime('2015-01-01'),
            'template'    => 'post.twig',
            'seoTitle'    => 'Search Optimized Title',
            'description' => 'The description',
            'excerpt'     => 'The excerpt',

            # Metadata
            'foo' => 'bar',
            'baz' => 'qux',

            # Taxonomies
            'categories' => ['Unix'],
            'tags'       => ['Tag 1'],
        ];
    }

    function it_transforms_an_array_into_front_matter()
    {
        $data = $this->getData();
        $this->transform($data)->shouldReturnAnInstanceOf(FrontMatter::class);
    }

    function it_converts_string_to_datetime_when_value_is_string()
    {
        $data = $this->getData();
        $data['date'] = "2016-04-01";
        $frontMatter = $this->transform($data);

        $dt = new \DateTime($data['date']);
        $date = $frontMatter->getDate();

        $frontMatter->getDate()->shouldBeLike($dt);
    }

    function it_throws_an_exception_if_date_is_int()
    {
        $data = $this->getData();
        $data['date'] = 1456480461;
        $this->shouldThrow(new TransformationFailure('YAML Front Matter dates must be quoted.'))
            ->duringTransform($data)
        ;
    }

    function it_throws_an_exception_when_using_a_taxonomy_key_in_front_matter_with_unregistered_term_as_value()
    {
        $data = ['tags' => ['Wolf']];
        $this->shouldThrow(new TransformationFailure("The Taxonomy 'Tag' does not contain term 'Wolf'."))
            ->duringTransform($data)
        ;
    }

    /**
     * @return Taxonomy
     */
    protected function getCategoryTaxonomy()
    {
        return Taxonomy::fromArray([
            'name'       => 'Category',
            'pluralName' => 'Categories',
            'uri'        => 'categories'
        ]);
    }

    /**
     * @return Taxonomy
     */
    protected function getTagTaxonomy()
    {
        return Taxonomy::fromArray([
            'name'       => 'Tag',
            'pluralName' => 'Tags',
            'uri'        => 'tags'
        ]);
    }
}
