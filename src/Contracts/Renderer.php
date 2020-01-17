<?php

declare(strict_types=1);

namespace Skimpy\Contracts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Render an Entity to a Response
 *
 * It could be the JSON representation or HTML
 * representation of an Entity.
 */
interface Renderer
{
    public function render(
        Entity $entity,
        Request $request,
        array $params = []
    ): Response;

    /**
     * Returns an array of response types the renderer should handle
     *
     * ['application/json']
     * ['text/html']
     */
    public function getMimeTypes(): array;
}
