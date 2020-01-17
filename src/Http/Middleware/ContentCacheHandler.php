<?php

declare(strict_types=1);

namespace Skimpy\Http\Middleware;

use SplFileInfo;
use Skimpy\Database\Populator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class ContentCacheHandler
{
    protected $populator;

    protected $buildIndicator;

    public function __construct(
        Populator $populator,
        Filesystem $filesystem,
        SplFileInfo $buildIndicator
    ) {
        $this->populator = $populator;
        $this->filesystem = $filesystem;
        $this->buildIndicator = $buildIndicator;
    }

    public function handleRequest(Request $request, ContainerInterface $container): void
    {
        # Rebuild on every request in dev environment
        if ($this->isAutoRebuildMode($container)) {
            $this->rebuildDatabase();

            return;
        }

        if ($this->dbHasNotBeenBuilt()) {
            $this->rebuildDatabase();

            return;
        }

        if ($this->isValidRebuildRequest($request, $container)) {
            $this->rebuildDatabase();
        }
    }

    protected function isAutoRebuildMode(ContainerInterface $container): bool
    {
        return true === $container->get('skimpy.auto_rebuild');
    }

    /**
     * Builds the DB and creates a file indicating
     * that the DB has been built at least once.
     */
    protected function rebuildDatabase(): void
    {
        $this->populator->populate();

        $path = $this->buildIndicator->getPathname();
        $content = 'Last Build: '.date('Y-m-d H:i:s');

        $this->filesystem->remove($path);
        $this->filesystem->dumpFile($path, $content);
    }

    protected function dbHasNotBeenBuilt(): bool
    {
        return false === $this->buildIndicator->isFile();
    }

    protected function isValidRebuildRequest(Request $request, ContainerInterface $container): bool
    {
        # No indication to rebuild site, do nothing.
        if (false === $request->query->has('rebuild')) {
            return false;
        }

        # No build key present to validate site owner
        # is the one attempting to rebuild. Do Nothing.
        if (empty($container->get('skimpy.build_key'))) {
            return false;
        }

        # build_key does match rebuild query param value. Do nothing.
        if ($container->get('skimpy.build_key') !== $request->query->get('rebuild')) {
            return false;
        }

        return true;
    }
}
