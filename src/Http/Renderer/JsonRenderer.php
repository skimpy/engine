<?php

declare(strict_types=1);

namespace Skimpy\Http\Renderer;

use Skimpy\Contracts\Entity;
use Skimpy\Contracts\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRenderer implements Renderer
{
    public function render(
        Entity $entity,
        Request $request,
        array $params = []
    ): Response
    {
        throw new \RuntimeException('JSON responses not yet supported');
    }

    public function getMimeTypes(): array
    {
        return ['application/json'];
    }
}
