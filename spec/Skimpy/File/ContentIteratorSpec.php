<?php namespace spec\Skimpy\File;

use Prophecy\Argument;
use spec\Skimpy\ObjectBehavior;
use Skimpy\File\Transformer\FileToContentItem;

class ContentIteratorSpec extends ObjectBehavior
{
    function let(FileToContentItem $transformer)
    {
        $this->beConstructedWith($this->getContentDir(), $transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\ContentIterator');
    }
}