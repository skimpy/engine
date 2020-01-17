<?php namespace spec\Skimpy\File;

use Prophecy\Argument;
use spec\Skimpy\ObjectBehavior;
use Skimpy\File\Transformer\FileToTaxonomy;

class TaxonomyIteratorSpec extends ObjectBehavior
{
    function let(FileToTaxonomy $fileToTaxonomy)
    {
        $this->beConstructedWith($this->getContentDir(), $fileToTaxonomy);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\TaxonomyIterator');
    }
}