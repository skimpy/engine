<?php

declare(strict_types=1);

namespace Skimpy\Http\Controller;

use Skimpy\CMS\Taxonomy;
use Skimpy\Contracts\Entity;
use Skimpy\Http\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This controller handles fetching and rendering entities based on the URI path.
 *
 * URIs directly map to file paths.
 *
 * /content/pages/about.md maps to pages/about
 * /content/my-blogpost.md maps to /my-blogpost
 */
class GetController extends BaseController
{
    /**
     * Converts the URI into a file path and
     * fetches the Entity the file represents.
     *
     * Renders the view with the entity available.
     */
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
        $path = trim($request->getPathInfo(), '/');

        return $this->stripPrefix($path);
    }

    private function stripPrefix(string $path): string
    {
        $rawPrefix = $this->uriPrefix;
        $normalizedPrefix = '/'.trim($rawPrefix, '/').'/';
        $path = str_replace(ltrim($normalizedPrefix, '/'), '', $path);

        return $path;
    }

    /**
     * If categories.yaml has_public_terms_route: false,
     * then /categories should 404.
     *
     * Otherwise the /categories page will list
     * links to category names (terms)
     */
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