<?php

declare(strict_types=1);

namespace Skimpy\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetController extends BaseController
{
    public function handle(Request $request): Response
    {
        $path = $this->getEntityPath($request);

        $entity = $this->getResolver()->resolve($path);

        if (is_null($entity)) {
            throw new NotFoundHttpException;
        }

        return $this->renderEntity($request, $entity);
    }

    private function getEntityPath(Request $request): string
    {
        $path = trim($request->getRequestUri(), '/');

        return $this->stripPrefix($path);
    }

    private function stripPrefix(string $path): string
    {
        $rawPrefix = $this->uriPrefix;
        $normalizedPrefix = '/'.trim($rawPrefix, '/').'/';
        $path = str_replace(ltrim($normalizedPrefix, '/'), '', $path);

        return $path;
    }
}
