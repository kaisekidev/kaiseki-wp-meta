<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @template T
 */
abstract class AbstractField implements FieldInterface
{
    private const NULLABLE_TYPE = 'null';
    private bool $isRequired;
    /** @var T|null */
    private $default;

    /**
     * @param T|null $default
     */
    protected function __construct($default = null)
    {
        $this->isRequired = $default !== null;
        $this->default = $default;
    }

    /**
     * @return T|null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return self<T>
     */
    public function withRequiredValue(): self
    {
        $clone = clone $this;
        $clone->isRequired = true;
        return $clone;
    }

    /**
     * @return self<T>
     */
    public function withOptionalValue(): self
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
     * @return array{type: string|array{string, string}}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->isRequired ? $this->getType() : [$this->getType(), self::NULLABLE_TYPE],
        ];
    }
}
