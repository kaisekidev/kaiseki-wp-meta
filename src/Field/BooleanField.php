<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type BooleanFieldArray array{
 *      type: string|array{string, string}
 * }
 */
final class BooleanField extends AbstractField
{
    private const TYPE_NAME = 'boolean';

    public static function create(?bool $default = null): self
    {
        return new self($default);
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
