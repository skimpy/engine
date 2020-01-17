<?php

namespace spec\Skimpy;

class ObjectBehavior extends \PhpSpec\ObjectBehavior
{
    /**
     * @return string
     */
    protected function getContentDir()
    {
        return realpath(__DIR__ . '/../_data/content');
    }

    /**
     * @return string
     */
    protected function getTaxonomyDir()
    {
        return realpath(__DIR__ . '/../_data/taxonomies');
    }
}
