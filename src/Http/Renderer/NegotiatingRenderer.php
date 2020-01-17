<?php

declare(strict_types=1);

namespace Skimpy\Http\Renderer;

use Negotiation\Negotiator;
use Skimpy\Contracts\Entity;
use Skimpy\Contracts\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Examines the request mime type(s) and selects
 * the appropriate renderer to render the request.
 *
 * Takes an array of renderers on construct.
 */
class NegotiatingRenderer implements Renderer
{
    protected $renderers;
    protected $negotiator;
    protected $defaultMimeType;

    public function __construct(
        array $renderers,
        Negotiator $negotiator = null,
        string $defaultMimeType = 'text/html'
    ) {
        $this->renderers = $renderers;
        $this->negotiator = $negotiator ?: new Negotiator;
        $this->defaultMimeType = $defaultMimeType;
    }

    public function render(Entity $entity, Request $request, ?array $params = []): Response
    {
        $mimeType = $this->getMimeType($request);

        return $this->getRendererByMimeType($mimeType)
            ->render($entity, $request, $params);
    }

    public function getMimeTypes(): array
    {
        $types = [];

        foreach ($this->renderers as $renderer) {
            $types = array_merge($types, $renderer->getMimeTypes());
        }

        return $types;
    }

    protected function getMimeType(Request $request): string
    {
        $acceptHeader = $this->negotiator->getBest(
            $request->headers->get('Accept'),
            $this->getMimeTypes()
        );

        if ($acceptHeader && $this->isAcceptableMimeType($acceptHeader->getType())) {
            return $acceptHeader->getType();
        }

        return $this->defaultMimeType;
    }

    protected function getRendererByMimeType(string $type): Renderer
    {
        foreach ($this->renderers as $renderer) {
            if (in_array($type, $renderer->getMimeTypes())) {
                return $renderer;
            }
        }

        throw new \LogicException("Could not find a renderer for mime type: $type");
    }

    protected function isAcceptableMimeType(string $type): bool
    {
        return in_array($type, $this->getMimeTypes());
    }
}
