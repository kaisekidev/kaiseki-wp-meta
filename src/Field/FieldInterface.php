<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

interface FieldInterface
{
    /**
     * The JSON-schema fragment describing this field, used for the REST API
     * (`show_in_rest`) and for nesting inside array/object fields.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * The WordPress/JSON scalar type name (`string`, `integer`, `number`,
     * `boolean`, `array`, `object`).
     */
    public function getType(): string;

    public function getDefault(): mixed;

    /**
     * Whether `null` is an accepted value (emitted as `["<type>", "null"]`).
     */
    public function isNullable(): bool;
}
