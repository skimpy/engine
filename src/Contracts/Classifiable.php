<?php

declare(strict_types=1);

namespace Skimpy\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Defines an entity that has the potential to have taxonomies and terms
 *
 * Currently, this is only 'ContentItem'
 */
interface Classifiable
{
    public function getTaxonomies(): ArrayCollection;

    public function getTerms(): ArrayCollection;
}
