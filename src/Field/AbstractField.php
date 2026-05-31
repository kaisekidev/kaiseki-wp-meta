<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

abstract class AbstractField implements FieldInterface
{
    private const NULLABLE_TYPE = 'null';
    private bool $isRequired;
    private mixed $default;

    protected function __construct(mixed $default = null)
    {
        $this->isRequired = $default !== null;
        $this->default = $default;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function withRequiredValue(): static
    {
        $clone = clone $this;
        $clone->isRequired = true;

        return $clone;
    }

    public function withOptionalValue(): static
    {
        $clone = clone $this;
        $clone->isRequired = false;

        return $clone;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @return array{type: array{string, string}|string}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->isRequired ? $this->getType() : [$this->getType(), self::NULLABLE_TYPE],
        ];
    }
}
