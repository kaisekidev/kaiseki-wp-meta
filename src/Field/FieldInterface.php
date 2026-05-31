<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

interface FieldInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function getDefault(): mixed;

    public function getType(): string;

    public function withRequiredValue(): self;

    public function withOptionalValue(): self;

    public function isRequired(): bool;

    public function isValidValue(mixed $value): bool;
}
