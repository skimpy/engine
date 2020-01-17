<?php namespace spec\Skimpy\File;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\Term;

class FrontMatterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\FrontMatter');
    }

    function it_has_a_settable_title()
    {
        $this->setTitle('Hello World');
        $this->getTitle()->shouldReturn('Hello World');
    }

    function it_has_a_settable_date()
    {
        $date = new DateTime('2015-01-01');
        $this->setDate($date);
        $this->getDate()->shouldBeLike($date);
    }

    function it_initializes_a_terms_collection()
    {
        $this->getTerms()->shouldHaveType(ArrayCollection::class);
    }

    function it_initializes_a_taxonomies_collection()
    {
        $this->getTaxonomies()->shouldHaveType(ArrayCollection::class);
    }

    function it_has_a_settable_seo_title()
    {
        $this->setSeoTitle('foo');
        $this->getSeoTitle()->shouldReturn('foo');
    }

    function it_can_have_a_description()
    {
        $description = "SEO meta description here";
        $this->setDescription($description);
        $this->getDescription()->shouldReturn($description);
    }

    function it_has_a_settable_excerpt()
    {
        $excerpt = "This is some excerpt text";

        $this->setExcerpt($excerpt);

        $this->getExcerpt()->shouldReturn($excerpt);
    }

    function it_has_a_settable_template()
    {
        $this->setTemplate('post');
        $this->getTemplate()->shouldReturn('post');
    }

    function it_can_have_metadata()
    {
        $this->setMeta('foo', 'bar');
        $this->getMeta('foo')->shouldReturn('bar');
    }

    function it_can_remove_metadata()
    {
        $this->setMeta('foo', 'bar');
        $this->removeMeta('foo');
        $this->getMeta('foo')->shouldReturn(null);
    }

    function it_knows_if_it_has_taxonomies()
    {
        $this->hasTaxonomies()->shouldReturn(false);
    }

    function it_can_have_terms(Term $term)
    {
        $this->addTerm($term);
        $this->getTerms()->first()->shouldReturn($term);
    }

    function it_collects_taxonomies_of_its_terms()
    {
        $this->getTaxonomies()->toArray()->shouldReturn([]);

        $taxonomy = new Taxonomy(
            'Category',
            'Categories',
            'categories'
        );

        $term = new Term($taxonomy, 'Web Development', 'web-development');

        $this->addTerm($term);

        $this->getTaxonomies()->toArray()->shouldContain($taxonomy);
    }

    function it_can_add_multiple_terms(Term $term1, Term $term2)
    {
        $this->addTerms([$term1, $term2]);
        $this->getTerms()->shouldHaveCount(2);
    }

    function it_wont_add_the_same_term_twice(Term $term)
    {
        $this->addTerm($term);
        $this->addTerm($term);
        $this->getTerms()->shouldHaveCount(1);
    }

    function it_has_configurable_keys()
    {
        $keys = ['title', 'seoTitle', 'date', 'description', 'excerpt', 'template'];
        $this->getConfigurableKeys()->shouldReturn($keys);
    }

    function it_dynamically_determines_if_a_property_has_a_value()
    {
        $this->setTitle(null);
        $this->has('title')->shouldReturn(false);
        $this->setTitle('Title');
        $this->has('title')->shouldReturn(true);
    }

    function it_throws_an_exception_if_argument_to_has_is_not_a_property()
    {
        $this->shouldThrow('\RuntimeException')->duringHas('foo');
    }
}