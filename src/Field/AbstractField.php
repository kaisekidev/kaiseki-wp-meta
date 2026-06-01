<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

abstract class AbstractField implements FieldInterface
{
    private const NULLABLE_TYPE = 'null';
    private bool $nullable = false;

    protected function __construct(private readonly mixed $default = null)
    {
    }

    /**
     * Allow `null` as a valid value. Independent of whether a default is set.
     */
    public function nullable(): static
    {
        $clone = clone $this;
        $clone->nullable = true;

        return $clone;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @return array{type: array{string, string}|string}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->nullable ? [$this->getType(), self::NULLABLE_TYPE] : $this->getType(),
        ];
    }
}
