<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function count;

/**
 * @phpstan-type ObjectFieldArray array{
 *      type: string|array{string, string},
 *      properties: array<string, array<string, mixed>>,
 *      required?: list<string>,
 *      default?: array<string, mixed>
 * }
 */
final class ObjectField implements FieldInterface
{
    private const TYPE_NAME = 'array';
    /** @var array<string, FieldInterface> */
    private array $properties = [];
    /** @var list<string> */
    private array $requiredFieldNames = [];
    /** @var array<string, mixed>|null */
    private ?array $default;

    /**
     * @param array<string, mixed>|null $default
     */
    private function __construct(?array $default = null)
    {
        $this->default = $default;
    }

    /**
     * @param array<string, mixed>|null $default
     */
    public static function create(?array $default = null): self
    {
        return new self($default);
    }

    public function withAddedProperty(string $name, FieldInterface $field, bool $required = false): self
    {
        $clone = clone $this;
        $clone->properties[$name] = $field;
        if ($required) {
            $clone->requiredFieldNames[] = $name;
        }
        return $clone;
    }

    /**
     * @phpstan-return ObjectFieldArray
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->default === null ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME,
        ];
        if (count($this->requiredFieldNames) > 0) {
            $array['required'] = $this->requiredFieldNames;
        }
        $array['properties'] = [];
        foreach ($this->properties as $name => $field) {
            $array['properties'][$name] = $field->toArray();
        }
        if ($this->default !== null) {
            $array['default'] = $this->default;
        }
        return $array;
    }

    /**
     * @return array<string, mixed>|null
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
