<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function count;

/**
 * @phpstan-type ObjectFieldArray array{
 *      type: string|array{string, string},
 *      properties: array<string, array<string, mixed>>,
 *      required?: list<string>
 * }
 */
final class ObjectField implements FieldInterface
{
    private const TYPE_NAME = 'object';
    /** @var array<string, FieldInterface> */
    private array $properties = [];
    /** @var list<string> */
    private array $requiredFieldNames = [];
    private bool $isNullAllowed = false;

    private function __construct()
    {
    }

    /**
     * @param array<string, FieldInterface> $properties Array index will be used as name for property
     */
    public static function create(?array $properties = null): self
    {
        $instance = new self();
        $instance->properties = $properties ?? [];
        return $instance;
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

    public function withNullAllowed(): self
    {
        $clone = clone $this;
        $clone->isNullAllowed = true;
        return $clone;
    }

    /**
     * @phpstan-return ObjectFieldArray
     */
    public function toArray(): array
    {
        $array = ['type' => $this->isNullAllowed ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME];
        if (count($this->requiredFieldNames) > 0) {
            $array['required'] = $this->requiredFieldNames;
        }
        $array['properties'] = [];
        foreach ($this->properties as $name => $field) {
            $array['properties'][$name] = $field->toArray();
        }
        return $array;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDefault(): ?array
    {
        $default = [];
        foreach ($this->properties as $name => $field) {
            $default[$name] = $field->getDefault();
        }
        return count($default) > 0 ? $default : null;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
