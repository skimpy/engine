<?php

declare(strict_types=1);

namespace Skimpy\Repo;

use Doctrine\ORM\EntityRepository;

abstract class BaseRepo extends EntityRepository
{
    public function save(): void
    {
        throw new \RuntimeException('Update the file instead.');
    }

    public function delete(): void
    {
        throw new \RuntimeException('Delete the file instead.');
    }

    public function getEntityShortName(): string
    {
        return basename(str_replace('\\', '/', $this->getClassName()));
    }
}
