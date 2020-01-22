<?php namespace spec\Skimpy\File;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaxonomyFileSpec extends ObjectBehavior
{
    function let()
    {
        $data = $this->getData();
        $config = [];
        $this->beConstructedWith($data['slug'], $data['name'], $data['pluralName'], $data['terms'], $config);
    }

    protected function getData()
    {
        return [
            'slug'       => 'categories',
            'name'       => 'Category',
            'pluralName' => 'Categories',
            'terms'      => $this->getTermData()
        ];
    }

    protected function getTermData()
    {
        return [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
            ],
            [
                'name' => 'Unix',
                'slug' => 'unix'
            ]
        ];
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\TaxonomyFile');
    }

    function it_requires_a_slug()
    {
        $this->getSlug()->shouldReturn('categories');
    }

    function it_requires_a_name()
    {
        $this->getName()->shouldReturn('Category');
    }

    function it_requires_a_plural_name()
    {
        $this->getPluralName()->shouldReturn('Categories');
    }

    function it_requires_terms()
    {
        $this->getTerms()->shouldReturn($this->getTermData());
    }
}
