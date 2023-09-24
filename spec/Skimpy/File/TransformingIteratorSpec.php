<?php

namespace spec\Skimpy\File;

use spec\Skimpy\ObjectBehavior;
use Skimpy\Symfony\FinderFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Skimpy\File\Transformer\NullTransformer;
use PhpSpec\Exception\Example\SkippingException;

class TransformingIteratorSpec extends ObjectBehavior
{
    function let(NullTransformer $transformer)
    {
        $path = $this->getContentDir();
        $this->beConstructedWith($path, $transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Skimpy\File\TransformingIterator');
    }

    function it_filters_by_file_extension(
        $transformer,
        FinderFactory $factory,
        Finder $finder
    ) {
        throw new SkippingException(
            'Update wont mock static return'
        );

        $path = $this->getContentDir();
        $this->beConstructedWith($path, $transformer, ['md'], $factory);

        $factory->createFinder()->willReturn($finder);
        $finder->files()->willReturn($finder);
        $finder->in($path)->shouldBeCalled();
        $finder->name('(\.md$)')->shouldBeCalled();
        $finder->getIterator()->willReturn(new \EmptyIterator);

        $this->getIterator();
    }

    function it_filters_by_multiple_file_extensions(
        $transformer,
        FinderFactory $factory,
        Finder $finder,
        \Iterator $iterator
    ) {
        throw new SkippingException(
            'Update wont mock static return'
        );

        $path = $this->getContentDir();
        $this->beConstructedWith($path, $transformer, ['md', 'yaml'], $factory);

        $factory->createFinder()->willReturn($finder);
        $finder->files()->willReturn($finder);
        $finder->in($path)->shouldBeCalled();
        $finder->name('(\.md$|\.yaml$)')->shouldBeCalled();
        $finder->getIterator()->willReturn($iterator);

        $this->getIterator()->shouldReturn($iterator);
    }

    function it_transforms_the_current_iteration($transformer, SplFileInfo $file)
    {
        $transformer->transform($file)->willReturn($file);
        $this->next();
        $this->current($file)->shouldReturn($file);
    }

    function it_returns_the_iterator_as_array()
    {
        $transformer = new NullTransformer;
        $this->beConstructedWith($this->getContentDir(), $transformer);
        $expected = iterator_to_array((new Finder)->files()->in($this->getContentDir()));
        $this->toArray()->shouldBeLike($expected);
    }
}