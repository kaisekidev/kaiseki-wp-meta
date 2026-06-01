<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\StringField;
use PHPUnit\Framework\TestCase;

/**
 * Exercises the shared {@see \Kaiseki\WordPress\Meta\Field\AbstractField}
 * behaviour through a concrete field (StringField).
 */
final class AbstractFieldTest extends TestCase
{
    public function testNonNullableByDefaultEvenWithDefault(): void
    {
        $field = StringField::create('hello');

        self::assertFalse($field->isNullable());
        self::assertSame('string', $field->toArray()['type']);
    }

    public function testNullableEmitsUnionType(): void
    {
        $field = StringField::create('hello')->nullable();

        self::assertTrue($field->isNullable());
        self::assertSame(['string', 'null'], $field->toArray()['type']);
    }

    public function testNullableIsImmutable(): void
    {
        $original = StringField::create();
        $nullable = $original->nullable();

        self::assertFalse($original->isNullable());
        self::assertTrue($nullable->isNullable());
        self::assertNotSame($original, $nullable);
    }

    public function testGetDefaultReturnsConfiguredDefault(): void
    {
        self::assertSame('hello', StringField::create('hello')->getDefault());
        self::assertNull(StringField::create()->getDefault());
    }
}
