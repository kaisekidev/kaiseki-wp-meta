<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @template T of int|float
 *
 * @phpstan-type NumericFieldArray array{
 *      type: string|array{string, string},
 *      minimum?: int|float,
 *      exclusiveMinimum?: bool,
 *      maximum?: int|float,
 *      exclusiveMaximum?: bool,
 *      multipleOf?: int|float
 * }
 */
abstract class AbstractNumericField extends AbstractField
{
    /** @var T|null */
    private int|float|null $minimum = null;
    private ?bool $exclusiveMinimum = null;
    /** @var T|null */
    private int|float|null $maximum = null;
    private ?bool $exclusiveMaximum = null;
    /** @var T|null */
    private int|float|null $multipleOf = null;

    /**
     * @param T $minimum
     */
    public function withMinimum(int|float $minimum): static
    {
        $new = clone $this;
        $new->minimum = $minimum;

        return $new;
    }

    public function withExclusiveMinimum(): static
    {
        $new = clone $this;
        $new->exclusiveMinimum = true;

        return $new;
    }

    /**
     * @param T $maximum
     */
    public function withMaximum(int|float $maximum): static
    {
        $new = clone $this;
        $new->maximum = $maximum;

        return $new;
    }

    public function withExclusiveMaximum(): static
    {
        $new = clone $this;
        $new->exclusiveMaximum = true;

        return $new;
    }

    /**
     * @param T $multipleOf
     */
    public function withMultipleOf(int|float $multipleOf): static
    {
        $new = clone $this;
        $new->multipleOf = $multipleOf;

        return $new;
    }

    /**
     * @phpstan-return NumericFieldArray
     */
    public function toArray(): array
    {
        $array = parent::toArray();
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
}
