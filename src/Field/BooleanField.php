<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type BooleanFieldArray array{
 *      type: string|array{string, string},
 *      default: bool
 * }
 */
final class BooleanField implements FieldInterface
{
    private const TYPE_NAME = 'boolean';
    private bool $default;

    private function __construct(bool $default = false)
    {
        $this->default = $default;
    }

    public static function create(bool $default = false): self
    {
        return new self($default);
    }

    /**
     * @phpstan-return BooleanFieldArray
     */
    public function toArray(): array
    {
        return [
            'type' => self::TYPE_NAME,
            'default' => $this->default,
        ];
    }

    public function getDefault(): bool
    {
        return $this->default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
