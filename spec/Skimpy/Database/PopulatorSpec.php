<?php namespace spec\Skimpy\Database;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\File\ContentIterator;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\ContentItem;

class PopulatorSpec extends ObjectBehavior
{
    function let(
        EntityManagerInterface $em,
        ContentIterator $contentIterator,
        SchemaTool $schemaTool,
        Taxonomy $tax
    ) {
        $this->beConstructedWith($em, $contentIterator, [$tax], $schemaTool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\Database\Populator');
    }

    function it_drops_and_rebuilds_the_database(
        SchemaTool $schemaTool,
        EntityManagerInterface $em,
        ClassMetadataFactory $factory,
        Taxonomy $tax,
        ContentItem $contentItem,
        ContentIterator $contentIterator
    ) {
        $schemaTool->dropDatabase()->shouldBeCalled();
        $em->getMetadataFactory()->willReturn($factory);
        $factory->getAllMetadata()->willReturn([]);

        $schemaTool->updateSchema([])->shouldBeCalled();

        $contentIterator->toArray()->willReturn([$contentItem]);
        $em->persist($contentItem)->shouldBeCalled();

        $em->persist($tax)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->populate();
    }
}