<?php

declare(strict_types=1);

namespace Skimpy\Http\Renderer;

use Twig\Environment;
use Skimpy\Contracts\Entity;
use Skimpy\Contracts\Renderer;
use Skimpy\Http\TemplateResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders an entity to HTML using Twig
 */
class TwigRenderer implements Renderer
{
    protected $twig;
    protected $templateResolver;

    public function __construct(Environment $twig, TemplateResolver $templateResolver = null)
    {
        $this->twig = $twig;
        $this->templateResolver = $templateResolver ?? new TemplateResolver;
    }

    public function render(Entity $entity, Request $request, ?array $params = []): Response
    {
        # params['entry'] = $entry
        $entityName = $entity->getEntityName();
        $template = $this->templateResolver->resolve($entity);

        $params = array_merge([$entityName => $entity], $params);

        return new Response(
            $this->twig->render($template, $params)
        );
    }

    public function getMimeTypes(): array
    {
        return ['text/html'];
    }
}
