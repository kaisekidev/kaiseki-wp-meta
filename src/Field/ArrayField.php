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
final class ArrayField implements FieldInterface
{
    private const TYPE_NAME = 'array';
    private FieldInterface $arrayField;
    /** @var array<int, mixed>|null */
    private ?array $default;
    private ?int $minItems = null;
    private ?int $maxItems = null;
    private ?bool $uniqueItems = null;

    /**
     * @param list<mixed>|null $default Default value must respect type given by $arrayField
     */
    private function __construct(FieldInterface $arrayField, ?array $default = null)
    {
        $this->arrayField = $arrayField;
        $this->default = $default;
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
        $array = [
            'type' => $this->default === null ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME,
            'items' => $this->arrayField->toArray(),
        ];
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

    /**
     * @return list<mixed>|null
     */
    public function getDefault(): ?array
    {
        return $this->default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
