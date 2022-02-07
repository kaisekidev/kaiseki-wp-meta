<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type IntegerFieldArray array{
 *      type: string|array{string, string},
 *      minimum?: int,
 *      exclusiveMinimum?: bool,
 *      maximum?: int,
 *      exclusiveMaximum?: bool,
 *      multipleOf?: int
 * }
 */
final class IntegerField implements FieldInterface
{
    private const TYPE_NAME = 'integer';
    private ?int $default;
    private ?int $minimum = null;
    private ?bool $exclusiveMinimum = null;
    private ?int $maximum = null;
    private ?bool $exclusiveMaximum = null;
    private ?int $multipleOf = null;

    private function __construct(?int $default = null)
    {
        $this->default = $default;
    }

    public static function create(?int $default = null): self
    {
        return new self($default);
    }

    public function withMinimum(int $minimum): self
    {
        $new = clone $this;
        $new->minimum = $minimum;
        return $new;
    }

    public function withExcludedMinimum(): self
    {
        $new = clone $this;
        $new->exclusiveMinimum = true;
        return $new;
    }

    public function withMaximum(int $maximum): self
    {
        $new = clone $this;
        $new->maximum = $maximum;
        return $new;
    }

    public function withExcludedMaximum(): self
    {
        $new = clone $this;
        $new->exclusiveMaximum = true;
        return $new;
    }

    public function withMultipleOf(int $multipleOf): self
    {
        $new = clone $this;
        $new->multipleOf = $multipleOf;
        return $new;
    }

    /**
     * @phpstan-return IntegerFieldArray
     */
    public function toArray(): array
    {
        $array = ['type' => $this->default === null ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME];
        if ($this->minimum !== null) {
            $array['minimum'] = $this->minimum;
        }
        if ($this->exclusiveMinimum !== null) {
            $array['exclusiveMinimum'] = $this->exclusiveMinimum;
        }
        if ($this->maximum !== null) {
            $array['maximum'] = $this->maximum;
        }
        if ($this->exclusiveMaximum !== null) {
            $array['exclusiveMaximum'] = $this->exclusiveMaximum;
        }
        if ($this->multipleOf !== null) {
            $array['multipleOf'] = $this->multipleOf;
        }
        return $array;
    }

    public function getDefault(): ?int
    {
        return $this->default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
