<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function array_key_exists;
use function count;
use function is_array;

/**
 * @phpstan-type ObjectFieldArray array{
 *      type: string|array{string, string},
 *      properties: array<string, array<string, mixed>>,
 *      required?: list<string>
 * }
 * @extends AbstractField<array<string, mixed>>
 */
final class ObjectField extends AbstractField
{
    private const TYPE_NAME = 'object';
    /** @var array<string, FieldInterface> */
    private array $properties = [];
    /** @var list<string> */
    private array $requiredFieldNames = [];

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

    /**
     * @phpstan-return ObjectFieldArray
     */
    public function toArray(): array
    {
        $array = parent::toArray();
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

    /**
     * @param mixed $value
     */
    public function isValidValue($value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $key => $arrayValue) {
            if (!array_key_exists($key, $this->properties)) {
                return false;
            }
            if (!$this->properties[$key]->isValidValue($arrayValue)) {
                return false;
            }
        }
        return true;
    }
}
