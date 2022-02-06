<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type NumberFieldArray array{
 *      type: string|array{string, string},
 *      default?: float,
 *      minimum?: float,
 *      exclusiveMinimum?: bool,
 *      maximum?: float,
 *      exclusiveMaximum?: bool,
 *      multipleOf?: float
 * }
 */
final class NumberField implements FieldInterface
{
    private const TYPE_NAME = 'number';
    private ?float $default;
    private ?float $minimum = null;
    private ?bool $exclusiveMinimum = null;
    private ?float $maximum = null;
    private ?bool $exclusiveMaximum = null;
    private ?float $multipleOf = null;

    private function __construct(?float $default = null)
    {
        $this->default = $default;
    }

    public static function create(?float $default = null): self
    {
        return new self($default);
    }

    public function withMinimum(float $minimum): self
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

    public function withMaximum(float $maximum): self
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

    public function withMultipleOf(float $multipleOf): self
    {
        $new = clone $this;
        $new->multipleOf = $multipleOf;
        return $new;
    }

    /**
     * @phpstan-return NumberFieldArray
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->default === null ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME,
        ];
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

    public function getDefault(): ?float
    {
        return $this->default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
