<?php

declare(strict_types=1);

namespace Skimpy\Http;

use Skimpy\CMS\ContentItem;
use Skimpy\Contracts\Entity;
use Skimpy\Symfony\FinderFactory;

class TemplateResolver
{
    private $finderFactory;

    public function __construct(?FinderFactory $finderFactory = null)
    {
        $this->finderFactory = $finderFactory ?? new FinderFactory;
    }

    public function resolve(Entity $entity): string
    {
        if ($entity->hasTemplate()) {
            return $entity->getTemplate();
        }

        if ($entity instanceof ContentItem && $entity->isIndex()) {
            return 'index.twig';
        }

        if ($entity instanceof ContentItem) {
            $uriSegments = array_reverse(explode('/', $entity->getUri()));
            $templates = $this->getTemplates();
            foreach ($uriSegments as $segment) {
                if (in_array($segment, $templates)) {
                    return $segment.'.twig';
                }
            }
        }

        return $entity->getEntityName() . '.twig';
    }

    private function getTemplates(): array
    {
        $finder = $this->finderFactory->createFinder();

        $files = $finder->in(base_path('site/templates'))->files();

        $paths = array_keys(iterator_to_array($files));

        $paths = array_map(function($path) {
            $path = str_replace('.twig', '', $path);
            return array_reverse(explode('templates/', $path))[0] ?? '';
        }, $paths);

        return $paths;
    }
}