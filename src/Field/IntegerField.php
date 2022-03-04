<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function is_int;

/**
 * @extends AbstractNumericField<int>
 */
final class IntegerField extends AbstractNumericField
{
    private const TYPE_NAME = 'integer';

    public static function create(?int $default = null): self
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
        return is_int($value);
    }
}
