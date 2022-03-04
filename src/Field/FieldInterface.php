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

    public function withRequiredValue(): self;

    public function withOptionalValue(): self;

    public function isRequired(): bool;

    /**
     * @param mixed $value
     */
    public function isValidValue($value): bool;
}
