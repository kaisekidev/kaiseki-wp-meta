<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function is_float;

/**
 * @extends AbstractNumericField<float>
 */
final class NumberField extends AbstractNumericField
{
    private const TYPE_NAME = 'number';

    public static function create(?float $default = null): self
    {
        return new self($default);
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param mixed $value
     */
    public function isValidValue($value): bool
    {
        return is_float($value);
    }
}
