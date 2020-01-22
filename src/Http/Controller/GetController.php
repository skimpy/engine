<?php

declare(strict_types=1);

namespace Skimpy\Http\Controller;

use Skimpy\CMS\Taxonomy;
use Skimpy\Contracts\Entity;
use Skimpy\Http\Controller\BaseController;
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

        $this->abortIfTaxonomyNoPublicTerms($entity);

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

    private function abortIfTaxonomyNoPublicTerms(Entity $entity): void
    {
        if (false === $entity instanceof Taxonomy) {
            return;
        }

        /** @var Taxonomy $entity */
        if ($entity->hasPublicTermsRoute()) {
            return;
        }

        throw new NotFoundHttpException;
    }
}
