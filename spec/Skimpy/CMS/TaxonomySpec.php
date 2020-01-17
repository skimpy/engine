<?php

namespace spec\Skimpy\CMS;

use Skimpy\CMS\Term;
use PhpSpec\ObjectBehavior;
use Skimpy\CMS\ContentItem;
use Doctrine\Common\Collections\ArrayCollection;

class TaxonomySpec extends ObjectBehavior
{
    function let()
    {
        $data = $this->getData();
        $this->beConstructedWith($data['name'], $data['pluralName'], $data['uri']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\CMS\Taxonomy');
    }

    function it_can_be_constructed_from_an_array()
    {
        $data = $this->getData();
        $this->beConstructedThrough('fromArray', [$data]);
        $this->getKey()->shouldReturn($data['uri']);
        $this->getName()->shouldReturn($data['name']);
        $this->getPluralName()->shouldReturn($data['pluralName']);
        $this->getUri()->shouldReturn($data['uri']);
        $this->getConfig()->shouldReturn([]);
        $this->getEntityName()->shouldReturn('taxonomy');
    }

    function it_can_store_a_unique_integer_id()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_requires_a_key()
    {
        $this->getKey()->shouldReturn('categories');
    }

    function it_requires_a_name()
    {
        $this->getName()->shouldReturn('Category');
    }

    function it_requires_a_plural_name()
    {
        $this->getPluralName()->shouldReturn('Categories');
    }

    function it_requires_a_uri()
    {
        $this->getUri()->shouldReturn('categories');
    }

    function it_has_many_terms(Term $term)
    {
        $this->hasTerm($term)->shouldReturn(false);
        $this->addTerm($term);
        $this->hasTerm($term)->shouldReturn(true);
        $this->removeTerm($term);
        $this->hasTerm($term)->shouldReturn(false);
    }

    function it_returns_its_key_when_cast_to_string()
    {
        $this->__toString()->shouldReturn('categories');
    }

    function it_is_iterable_using_its_terms(Term $term, Term $term2)
    {
        $this->addTerm($term);
        $this->addTerm($term2);
        $this->getTerms()->count()->shouldReturn(2);
        $this->getIterator()->shouldReturn($this->getTerms());
    }

    function it_has_entries_through_terms(Term $term, ContentItem $entry)
    {
        $term->addContentItem($entry);
        $term->getEntries()->willReturn(new ArrayCollection([$entry]));

        $this->addTerm($term);
        $this->getEntries()->shouldHaveCount(1);
    }

    function it_can_retrieve_one_if_its_terms_by_name()
    {
        $term = new Term($this->getWrappedObject(), 'Term Name', 'term-slug');
        $this->getTermByName('Term Name')->shouldReturn($term);
    }

    function it_can_retrieve_one_of_its_terms_by_slug(Term $term)
    {
        $term = new Term($this->getWrappedObject(), 'Term Name', 'term-slug');
        $this->getTermBySlug('term-slug')->shouldReturn($term);
    }

    function it_is_countable_through_its_terms(Term $term)
    {
        $this->addTerm($term);
        $this->count()->shouldReturn(1);
    }

    function it_enables_public_term_routes_by_default()
    {
        $this->hasPublicTermsRoute()->shouldReturn(true);
    }

    function it_allows_disabling_public_terms_route_through_config()
    {
        $config = ['has_public_terms_route' => false];
        $this->beConstructedWith('Category', 'Categories', 'categories', $config);
        $this->hasPublicTermsRoute()->shouldReturn(false);
    }

    protected function getData()
    {
        return [
            'name'       => 'Category',
            'pluralName' => 'Categories',
            'uri'        => 'categories'
        ];
    }
}
