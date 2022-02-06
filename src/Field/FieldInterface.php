<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

interface FieldInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * @return mixed
     */
    public function getDefault();

    public function getType(): string;
}
