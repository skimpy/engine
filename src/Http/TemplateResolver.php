<?php

declare(strict_types=1);

namespace Skimpy\Http;

use Skimpy\Contracts\Entity;

class TemplateResolver
{
    public function resolve(Entity $entity): string
    {
        if ($entity->hasTemplate()) {
            return $entity->getTemplate();
        }

        # if metadata has template, use template
        # if entry, entry.twig
        # if entry && index, index.twig
        # if taxonomy, taxonomy.twig
        return $entity->getEntityName() . '.twig';
    }
}