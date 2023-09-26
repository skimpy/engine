<?php

declare(strict_types=1);

namespace Skimpy\CMS;

use Doctrine\Common\Collections\ArrayCollection;

trait Metadata
{
    /**
     * @ORM\Column(type="json_array")
     */
    protected $metadata = [];

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getMeta(string $key): mixed
    {
        return (new ArrayCollection($this->metadata))->get($key);
    }

    public function setMeta(string $key, $value): self
    {
        $this->metadata[$key] = $value;

        return $this;
    }

    public function removeMeta(string $key)
    {
        $metadata = new ArrayCollection($this->metadata);
        $removed = $metadata->remove($key);
        $this->metadata = $metadata->toArray();

        return $removed;
    }
}
