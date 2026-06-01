<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @extends AbstractNumericField<float|int>
 */
final class NumberField extends AbstractNumericField
{
    private const TYPE_NAME = 'number';

    public static function create(int|float|null $default = null): self
    {
        return new self($default);
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
