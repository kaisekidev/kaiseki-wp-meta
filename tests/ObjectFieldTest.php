<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;
use PHPUnit\Framework\TestCase;

final class ObjectFieldTest extends TestCase
{
    public function testCreate(): void
    {
        $result = ObjectField::create()->toArray();

        self::assertSame('object', $result['type']);
    }

    public function testGetType(): void
    {
        self::assertSame('object', ObjectField::create()->getType());
    }

    public function testPropertyLessObjectEmitsNoPropertiesKey(): void
    {
        $result = ObjectField::create()->toArray();

        self::assertArrayNotHasKey('properties', $result);
        self::assertArrayHasKey('additionalProperties', $result);
        self::assertFalse($result['additionalProperties']);
    }

    public function testWithPropertyEmitsPropertiesSchema(): void
    {
        $result = ObjectField::create()
            ->withProperty('name', StringField::create())
            ->toArray();

        self::assertArrayHasKey('properties', $result);
        self::assertSame(['type' => 'string'], $result['properties']['name']);
        self::assertArrayNotHasKey('required', $result);
    }

    public function testRequiredPropertiesAreListed(): void
    {
        $result = ObjectField::create()
            ->withProperty('name', StringField::create(), required: true)
            ->withProperty('age', IntegerField::create())
            ->toArray();

        self::assertArrayHasKey('required', $result);
        self::assertSame(['name'], $result['required']);
    }

    public function testWithAdditionalPropertiesTrue(): void
    {
        $result = ObjectField::create()->withAdditionalProperties(true)->toArray();

        self::assertTrue($result['additionalProperties']);
    }

    public function testCreateFromPropertiesMap(): void
    {
        $result = ObjectField::create([
            'name' => StringField::create(),
        ])->toArray();

        self::assertArrayHasKey('properties', $result);
        self::assertArrayHasKey('name', $result['properties']);
    }

    public function testWithPropertyIsImmutable(): void
    {
        $original = ObjectField::create();
        $extended = $original->withProperty('name', StringField::create());

        self::assertArrayNotHasKey('properties', $original->toArray());
        self::assertArrayHasKey('properties', $extended->toArray());
    }

    public function testGetDefaultAssemblesFromProperties(): void
    {
        $field = ObjectField::create()
            ->withProperty('name', StringField::create('anon'))
            ->withProperty('age', IntegerField::create());

        self::assertSame(['name' => 'anon', 'age' => null], $field->getDefault());
    }

    public function testGetDefaultIsNullWhenNoProperties(): void
    {
        self::assertNull(ObjectField::create()->getDefault());
    }
}
