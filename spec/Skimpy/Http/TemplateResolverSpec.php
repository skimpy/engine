<?php namespace spec\Skimpy\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\CMS\ContentItem;
use Skimpy\Contracts\Entity;

class TemplateResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\Http\TemplateResolver');
    }

    function it_uses_entry_template_for_entry_entities(Entity $entity)
    {
        $entity->hasTemplate()->willReturn(false);
        $entity->getEntityName()->willReturn('entry');

        $this->resolve($entity)->shouldReturn('entry.twig');
    }

    function it_uses_taxonomy_template_for_taxonomy_entities(Entity $entity)
    {
        $entity->hasTemplate()->willReturn(false);
        $entity->getEntityName()->willReturn('taxonomy');

        $this->resolve($entity)->shouldReturn('taxonomy.twig');
    }

    function it_uses_term_template_for_term_entities(Entity $entity)
    {
        $entity->hasTemplate()->willReturn(false);
        $entity->getEntityName()->willReturn('term');

        $this->resolve($entity)->shouldReturn('term.twig');
    }

    function it_should_use_metadata_template_if_provided(ContentItem $entity)
    {
        $entity->hasTemplate()->willReturn(true);
        $entity->getTemplate()->willReturn('foo.twig');

        $this->resolve($entity)->shouldReturn('foo.twig');
    }

    // function xit_spec()
    // {
        // URI: /categories
        // TYPE: Taxonomy
        // 1. taxonomy.twig
        // 2. categories/categories.twig

        // URI: /foo
        // TYPE: Entry
        // 1. entry.twig
        // 2. entry.template.twig

        // URI: /categories/web-development
        // TYPE: Term
        // 1. term.twig
        // 2. term.taxonomy.name/term.name.twig
    // }
}
