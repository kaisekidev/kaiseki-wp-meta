<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function count;
use function in_array;

/**
 * @phpstan-type ObjectFieldArray array{
 *      type: string|array{string, string},
 *      additionalProperties: bool,
 *      properties?: array<string, array<string, mixed>>,
 *      required?: list<string>
 * }
 */
final class ObjectField extends AbstractField
{
    private const TYPE_NAME = 'object';
    /** @var array<string, FieldInterface> */
    private array $properties = [];
    /** @var list<string> */
    private array $requiredFieldNames = [];
    private bool $additionalProperties = false;

    /**
     * @param array<string, FieldInterface> $properties array key is used as the property name
     */
    public static function create(array $properties = []): self
    {
        $instance = new self();
        $instance->properties = $properties;

        return $instance;
    }

    public function withProperty(string $name, FieldInterface $field, bool $required = false): self
    {
        $clone = clone $this;
        $clone->properties[$name] = $field;
        if ($required && !in_array($name, $clone->requiredFieldNames, true)) {
            $clone->requiredFieldNames[] = $name;
        }

        return $clone;
    }

    /**
     * Allow keys beyond the declared properties (default: closed object).
     *
     * @param bool $allow
     */
    public function withAdditionalProperties(bool $allow = true): self
    {
        $clone = clone $this;
        $clone->additionalProperties = $allow;

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
        if (count($this->properties) > 0) {
            $properties = [];
            foreach ($this->properties as $name => $field) {
                $properties[$name] = $field->toArray();
            }
            $array['properties'] = $properties;
        }
        $array['additionalProperties'] = $this->additionalProperties;

        return $array;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDefault(): ?array
    {
        if (count($this->properties) === 0) {
            return null;
        }
        $default = [];
        foreach ($this->properties as $name => $field) {
            $default[$name] = $field->getDefault();
        }

        return $default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
