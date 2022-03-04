<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type NumericFieldArray array{
 *      type: string|array{string, string},
 *      minimum?: T,
 *      exclusiveMinimum?: bool,
 *      maximum?: T,
 *      exclusiveMaximum?: bool,
 *      multipleOf?: T
 * }
 * @template T
 * @extends AbstractField<T>
 */
abstract class AbstractNumericField extends AbstractField
{
    /** @var T|null */
    private $minimum = null;
    private ?bool $exclusiveMinimum = null;
    /** @var T|null */
    private $maximum = null;
    private ?bool $exclusiveMaximum = null;
    /** @var T|null */
    private $multipleOf = null;

    /**
     * @param T $minimum
     * @return self<T>
     */
    public function withMinimum($minimum): self
    {
        $new = clone $this;
        $new->minimum = $minimum;
        return $new;
    }

    /**
     * @return self<T>
     */
    public function withExcludedMinimum(): self
    {
        $new = clone $this;
        $new->exclusiveMinimum = true;
        return $new;
    }

    /**
     * @param T $maximum
     * @return self<T>
     */
    public function withMaximum($maximum): self
    {
        $new = clone $this;
        $new->maximum = $maximum;
        return $new;
    }

    /**
     * @return self<T>
     */
    public function withExcludedMaximum(): self
    {
        $new = clone $this;
        $new->exclusiveMaximum = true;
        return $new;
    }

    /**
     * @param T $multipleOf
     * @return self<T>
     */
    public function withMultipleOf($multipleOf): self
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
