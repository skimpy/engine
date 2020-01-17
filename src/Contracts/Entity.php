<?php

declare(strict_types=1);

namespace Skimpy\Contracts;

interface Entity
{
    public static function fromArray(array $data);

    /**
     * Optional integer ID (for database)
     */
    public function getId(): ?int;

    /**
     * The unique string ID
     */
    public function getKey(): string;

    public function getEntityName(): string;

    public function hasTemplate(): bool;

}
