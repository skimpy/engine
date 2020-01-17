<?php namespace spec\Skimpy\CMS;

use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\Term;
use Skimpy\CMS\ContentItem;
use Doctrine\Common\Collections\ArrayCollection;

class TermSpec extends ObjectBehavior
{
    function let()
    {
        $tax = $this->getTestTaxonomy();
        $data = ['taxonomy' => $tax, 'name' => 'Web Development', 'slug' => 'web-development'];
        $this->beConstructedWith($data['taxonomy'], $data['name'], $data['slug']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\CMS\Term');
    }

    function it_can_be_constructed_from_an_array()
    {
        $data = [
        	'taxonomy' => $this->getTestTaxonomy(),
        	'name' => 'Web Development',
			'slug'  => 'web-development',
        ];

        $term = $this::fromArray($data);
        $term->shouldHaveType(Term::class);
        $term->getTaxonomy()->shouldReturn($data['taxonomy']);
        $term->getName()->shouldReturn($data['name']);
        $term->getSlug()->shouldReturn($data['slug']);
    }

    function it_can_store_a_unique_integer_id()
    {
    	$this->getId()->shouldReturn(null);
    }

    function it_requires_a_name()
    {
        $this->getName()->shouldReturn('Web Development');
    }

    function it_requires_a_slug()
    {
        $this->getSlug()->shouldReturn('web-development');
    }

    function it_belongs_to_one_taxonomy()
    {
        $this->getTaxonomy()->shouldHaveType(Taxonomy::class);
    }

    function it_sets_both_sides_of_the_taxonomy_term_relationship()
    {
        $this->getTaxonomy()->getTerms()->first()->shouldReturn($this);
    }

    function it_uses_the_taxonomy_uri_plus_its_own_slug_as_the_uri()
    {
        $this->getUri()->shouldReturn('categories/web-development');
    }

    function it_uses_the_uri_as_the_key()
    {
        $this->getKey()->shouldReturn($this->getUri());
    }

    function it_can_have_many_entries()
    {
        $this->getEntries()->shouldHaveType(ArrayCollection::class);
        $ci = new ContentItem('hello-world', 'Hello World', new DateTime('2015-01-01'), 'post', 'the content', 'post');
        $this->addContentItem($ci);
        $this->getEntries()->toArray()->shouldContain($ci);
    }

    function it_can_have_entries_removed()
    {
        $ci = new ContentItem('hello-world', 'Hello World', new DateTime('2015-01-01'), 'post', 'the content', 'post');
        $this->addContentItem($ci);
        $this->removeContentItem($ci);
        $this->getEntries()->toArray()->shouldReturn([]);
    }

    function it_returns_the_key_when_cast_to_string()
    {
        $this->__toString()->shouldReturn($this->getKey());
    }

    protected function getTestTaxonomy()
    {
        $tax = [
            'name'       => 'Category',
            'pluralName' => 'Categories',
            'uri'        => 'categories'
        ];

        return Taxonomy::fromArray($tax);
    }
}
