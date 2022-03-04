<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function gettype;
use function is_array;

/**
 * @phpstan-type ArrayFieldArray array{
 *      type: (string|array{string, string}),
 *      items: array<string, mixed>,
 *      minItems?: int,
 *      maxItems?: int,
 *      uniqueItems?: bool
 * }
 * @extends AbstractField<list<mixed>>
 */
final class ArrayField extends AbstractField
{
    private const TYPE_NAME = 'array';
    private FieldInterface $arrayField;
    private ?int $minItems = null;
    private ?int $maxItems = null;
    private ?bool $uniqueItems = null;

    /**
     * @param list<mixed>|null $default
     */
    private function __construct(FieldInterface $arrayField, ?array $default = null)
    {
        parent::__construct($default);
        $this->arrayField = $arrayField;
        if ($default === null) {
            return;
        }

        foreach ($default as $value) {
            if ($this->arrayField->isValidValue($value)) {
                continue;
            }
            throw new \InvalidArgumentException(
                \Safe\sprintf(
                    'ArrayField expects an array of %s, but contains %s',
                    $this->arrayField->getType(),
                    gettype($value)
                )
            );
        }
    }

    /**
     * @param list<mixed>|null $default
     */
    public static function create(FieldInterface $arrayField, ?array $default = null): self
    {
        return new self($arrayField, $default);
    }

    public function withMinItems(int $minItems): self
    {
        $new = clone $this;
        $new->minItems = $minItems;
        return $new;
    }

    public function withMaxItems(int $maxItems): self
    {
        $new = clone $this;
        $new->maxItems = $maxItems;
        return $new;
    }

    public function withUniqueItems(): self
    {
        $new = clone $this;
        $new->uniqueItems = true;
        return $new;
    }

    /**
     * @phpstan-return ArrayFieldArray
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['items'] = $this->arrayField->withRequiredValue()->toArray();
        if ($this->minItems !== null) {
            $array['minItems'] = $this->minItems;
        }
        if ($this->maxItems !== null) {
            $array['maxItems'] = $this->maxItems;
        }
        if ($this->uniqueItems !== null) {
            $array['uniqueItems'] = $this->uniqueItems;
        }
        return $array;
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
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $item) {
            if ($this->arrayField->isValidValue($item)) {
                continue;
            }
            return false;
        }
        return true;
    }
}
