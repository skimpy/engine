<?php

namespace spec\Skimpy;

class ObjectBehavior extends \PhpSpec\ObjectBehavior
{
    protected function getContentDir(): string
    {
        return realpath(__DIR__ . '/../_data/content');
    }

    protected function getDataDir(): string
    {
        return realpath(__DIR__ . '/../_data');
    }

    protected function getTaxonomyDir(): string
    {
        return realpath(__DIR__ . '/../_data/taxonomies');
    }
}
