<?php

declare(strict_types=1);

namespace Skimpy\Http\Controller;

use Skimpy\Repo\Entries;
use Skimpy\CMS\ContentItem;
use Skimpy\Contracts\Entity;
use Skimpy\CMS\EntityResolver;
use Skimpy\Contracts\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    protected $resolver;
    protected $renderer;
    protected $entries;
    protected $uriPrefix;

    public function __construct(
        EntityResolver $resolver,
        Renderer $renderer,
        Entries $entries,
        string $uriPrefix
    ) {
        $this->resolver = $resolver;
        $this->renderer = $renderer;
        $this->entries = $entries;
        $this->uriPrefix = $uriPrefix;
    }

    protected function renderEntity(Request $request, Entity $entity): Response
    {
        $params = $this->getImplicitParams($entity);

        return $this->getRenderer()->render($entity, $request, $params);
    }

    protected function getImplicitParams(Entity $entity): array
    {
        if ($entity instanceof ContentItem && $entity->isIndex()) {

            $entries = $this->entries->getIndexEntries($entity);

            return [
                'entries' => $entries,
            ];
        }

        return [];
    }

    protected function getResolver(): EntityResolver
    {
        return $this->resolver;
    }

    protected function getRenderer(): Renderer
    {
        return $this->renderer;
    }
}
