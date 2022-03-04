<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function is_bool;

/**
 * @phpstan-type BooleanFieldArray array{
 *      type: string|array{string, string}
 * }
 * @extends AbstractField<bool>
 */
final class BooleanField extends AbstractField
{
    private const TYPE_NAME = 'boolean';

    private function __construct(bool $default)
    {
        parent::__construct($default);
    }

    public static function create(bool $default): self
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
        return is_bool($value);
    }
}
