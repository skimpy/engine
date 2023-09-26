<?php

namespace spec\Skimpy\File;

use spec\Skimpy\ObjectBehavior;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Skimpy\File\Transformer\NullTransformer;

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

    function it_filters_by_file_extension($transformer)
    {
        $path = $this->getDataDir();

        $this->beConstructedWith($path, $transformer, ['md']);

        $this->getIterator()->shouldHaveCount(2);
    }

    function it_filters_by_multiple_file_extensions($transformer)
    {
        $path = $this->getDataDir();

        $this->beConstructedWith($path, $transformer, ['md', 'yaml']);

        # 2 yaml files
        # +
        # 2 markdown files
        $this->getIterator()->shouldHaveCount(4);
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