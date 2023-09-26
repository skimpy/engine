<?php namespace spec\Skimpy\CMS;

use Skimpy\CMS\Term;
use Skimpy\Repo\Terms;
use Skimpy\CMS\Taxonomy;
use Skimpy\Repo\Entries;
use PhpSpec\ObjectBehavior;
use Skimpy\Repo\Taxonomies;
use Skimpy\CMS\ContentItem as Entry;

class EntityResolverSpec extends ObjectBehavior
{
    function let(Entries $entries, Taxonomies $taxonomies, Terms $terms)
    {
        $this->beConstructedWith($entries, $taxonomies, $terms);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\CMS\EntityResolver');
    }

    function it_resolves_a_uri_to_an_entry(Entries $entries, Entry $entry)
    {
        $uri = 'example';

        $entries->findOneBy(['uri' => $uri])->willReturn($entry);

        $this->resolve($uri)->shouldHaveType(Entry::class);
    }

    function it_resolves_a_uri_to_a_taxonomy(
        Entries $entries,
        Taxonomies $taxonomies,
        Taxonomy $taxonomy
    ) {
        $uri = 'example';

        $entries->findOneBy(['uri' => $uri])->willReturn(null);
        $taxonomies->findOneBy(['uri' => $uri])->willReturn($taxonomy);

        $taxonomy->hasPublicTermsRoute()->willReturn(true);

        $this->resolve($uri)->shouldHaveType(Taxonomy::class);
    }

    function it_does_not_return_matching_taxonomy_if_the_taxonomy_has_no_public_terms_route(
        Entries $entries,
        Taxonomies $taxonomies,
        Taxonomy $taxonomy
    ) {
        $uri = 'example';

        $entries->findOneBy(['uri' => $uri])->willReturn(null);
        $taxonomies->findOneBy(['uri' => $uri])->willReturn($taxonomy);

        $taxonomy->hasPublicTermsRoute()->willReturn(false);

        $this->resolve($uri)->shouldReturn(null);
    }

    function it_resolves_a_uri_to_a_term(
        Entries $entries,
        Taxonomies $taxonomies,
        Terms $terms,
        Term $term
    ) {
        $uri = 'example';

        $entries->findOneBy(['uri' => $uri])->willReturn(null);
        $taxonomies->findOneBy(['uri' => $uri])->willReturn(null);

        $terms->findOneBy(['uri' => $uri])->willReturn($term);

        $this->resolve($uri)->shouldHaveType(Term::class);
    }

    function it_returns_the_repo_for_the_entity(Taxonomies $taxonomies)
    {
        $taxonomy = Taxonomy::fromArray(['name' => 'foo', 'pluralName' => 'foos', 'uri' => 'foo']);
        $this->getRepository($taxonomy)->shouldReturn($taxonomies);
    }

    function it_throws_an_exception_if_no_repo_is_found_for_the_entity($unknownEntity)
    {
        $unknownEntity->beADoubleOf('Skimpy\Contracts\Entity');

        $this->shouldThrow(\LogicException::class)->during('getRepository', [$unknownEntity]);
    }
}
