<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

interface MetaDataInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function getObjectType(): string;

    public function getMetaKey(): string;
}
