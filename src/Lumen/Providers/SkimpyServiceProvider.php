<?php

declare(strict_types=1);

namespace Skimpy\Lumen\Providers;

use Skimpy\Skimpy;
use Twig\TwigFilter;
use Skimpy\CMS\Term;
use Skimpy\Repo\Terms;
use Skimpy\CMS\Taxonomy;
use Skimpy\Repo\Entries;
use Skimpy\CMS\ContentItem;
use Skimpy\Repo\Taxonomies;
use Laravel\Lumen\Application;
use Skimpy\CMS\EntityResolver;
use Skimpy\Contracts\Renderer;
use Skimpy\Database\Populator;
use Skimpy\File\ContentIterator;
use Skimpy\File\TaxonomyIterator;
use Skimpy\Http\Renderer\JsonRenderer;
use Skimpy\Http\Renderer\TwigRenderer;
use Skimpy\Http\Controller\GetController;
use Skimpy\File\Transformer\FileToTaxonomy;
use Symfony\Component\Filesystem\Filesystem;
use Skimpy\Http\Renderer\NegotiatingRenderer;
use Skimpy\Lumen\Http\ContentCacheMiddleware;
use Skimpy\File\Transformer\FileToContentFile;
use Skimpy\File\Transformer\FileToContentItem;
use Skimpy\File\Transformer\ArrayToFrontMatter;
use Skimpy\File\Transformer\FileToTaxonomyFile;
use Skimpy\Http\Middleware\ContentCacheHandler;
use LaravelDoctrine\ORM\DoctrineServiceProvider;
use Skimpy\Lumen\Console\Commands\BuildDatabase;

class SkimpyServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var \Skimpy\Application
     */
    protected $app;

    public function register()
    {
        $this->app->configure('app');
        $this->app->configure('skimpy');

        $this->doctrine();
        $this->databasePopulator();
        $this->repositories();
        $this->uriPrefix();
        $this->twig();
        $this->responseRenderer();
        $this->contentCacheHandler();
        $this->controllers();
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BuildDatabase::class
            ]);
        }

        $router = $this->app->router;

        $router->group([], function ($router) {
            require __DIR__ . '/../routes.php';
        });

        $this->app->routeMiddleware([
            'skimpy.cache' => ContentCacheMiddleware::class,
        ]);

        $this->loadViewsFrom(base_path('site/templates'), 'skimpy');
    }

    private function doctrine(): void
    {
        $this->app->register(DoctrineServiceProvider::class);
    }

    private function databasePopulator(): void
    {
        $taxonomyPath = base_path('site/taxonomies');
        $contentPath = base_path('site/content');

        $taxonomyIterator = new TaxonomyIterator(
            $taxonomyPath,
            new FileToTaxonomy(new FileToTaxonomyFile)
        );

        $taxonomies = iterator_to_array($taxonomyIterator);

        $entryIterator = new ContentIterator(
            $contentPath,
            new FileToContentItem(new FileToContentFile(new ArrayToFrontMatter($taxonomies)))
        );

        $this->app->instance(ContentIterator::class, $entryIterator);
        $this->app->instance(TaxonomyIterator::class, $taxonomyIterator);

        $this->app->singleton(Populator::class, function (Application $app) use ($entryIterator, $taxonomies) {
            return new Populator(
                $app->get('em'),
                $entryIterator,
                $taxonomies
            );
        });
    }

    private function uriPrefix(): void
    {
        $this->app->singleton('skimpy.uri_prefix', function() {
            $prefix = config('skimpy.uri_prefix');
            return '/' === $prefix ? '' : '/' . trim($prefix, '/') . '/';
        });
    }

    private function repositories(): void
    {
        $this->app->singleton(Skimpy::class, function (Application $app) {
            return new Skimpy($app->get('em'));
        });

        $this->app->singleton(Entries::class, function (Application $app) {
            return $app->get('em')->getRepository(ContentItem::class);
        });

        $this->app->singleton(Taxonomies::class, function (Application $app) {
            return $app->get('em')->getRepository(Taxonomy::class);
        });

        $this->app->singleton(Terms::class, function (Application $app) {
            return $app->get('em')->getRepository(Term::class);
        });

        $this->app->alias(Skimpy::class, 'skimpy');
        $this->app->alias(Entries::class, 'skimpy.entries');
        $this->app->alias(Taxonomies::class, 'skimpy.taxonomies');
        $this->app->alias(Terms::class, 'skimpy.terms');
    }

    private function twig(): void
    {
        $this->app->configure('twigbridge');
        $this->app->register('TwigBridge\ServiceProvider');

        $defaultDateFormat = new TwigFilter('date_default_format', function (\DateTime $date) {
            return $date->format(config('skimpy.site.date_format', 'Y-m-d H:i:s'));
        });

        $twig = $this->app->get('twig');
        $twig->addFilter($defaultDateFormat);

        $config = new \Twig\TwigFunction('config', function ($key) {
            return config($key);
        });

        $twig->addFunction($config);

        $twig->addGlobal('skimpy', new \Skimpy\View\Model($this->app));
    }

    private function responseRenderer(): void
    {
        $this->app->singleton(NegotiatingRenderer::class, function (Application $app) {
            return new NegotiatingRenderer(
                [
                    new TwigRenderer($app->get('twig')),
                    new JsonRenderer,
                ]
            );
        });

        $this->app->bind(Renderer::class, NegotiatingRenderer::class);
    }

    private function contentCacheHandler(): void
    {
        $this->app->singleton(ContentCacheHandler::class, function(Application $app) {
            $populator = $app->get(Populator::class);
            $filesystem = new Filesystem;
            $buildIndicator = new \SplFileInfo($this->skimpyPath().'/.seeded');

            return new ContentCacheHandler($populator, $filesystem, $buildIndicator);
        });
    }

    private function controllers(): void
    {
        $this->app->singleton(GetController::class, function (Application $app) {
            $resolver = $app->get(EntityResolver::class);
            $renderer = $app->get(Renderer::class);
            $entries = $app->get(Entries::class);

            return new GetController($resolver, $renderer, $entries, $app->get('skimpy.uri_prefix'));
        });
    }

    private function skimpyPath(): string
    {
        return base_path();
    }
}
