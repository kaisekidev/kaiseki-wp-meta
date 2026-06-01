<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type ArrayFieldArray array{
 *      type: (string|array{string, string}),
 *      items: array<string, mixed>,
 *      minItems?: int,
 *      maxItems?: int,
 *      uniqueItems?: bool
 * }
 */
final class ArrayField extends AbstractField
{
    private const TYPE_NAME = 'array';
    private ?int $minItems = null;
    private ?int $maxItems = null;
    private ?bool $uniqueItems = null;

    /**
     * @param FieldInterface   $itemField
     * @param list<mixed>|null $default
     */
    private function __construct(private readonly FieldInterface $itemField, ?array $default = null)
    {
        parent::__construct($default);
    }

    /**
     * @param FieldInterface   $itemField
     * @param list<mixed>|null $default
     */
    public static function create(FieldInterface $itemField, ?array $default = null): self
    {
        return new self($itemField, $default);
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
        $array['items'] = $this->itemField->toArray();
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
}
