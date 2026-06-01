<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\StringField;
use PHPUnit\Framework\TestCase;

final class ArrayFieldTest extends TestCase
{
    public function testCreate(): void
    {
        $field = ArrayField::create(StringField::create());

        self::assertSame('array', $field->toArray()['type']);
        self::assertNull($field->getDefault());
    }

    public function testGetType(): void
    {
        self::assertSame('array', ArrayField::create(StringField::create())->getType());
    }

    public function testToArrayEmitsItemSchemaDirectly(): void
    {
        $result = ArrayField::create(StringField::create())->toArray();

        self::assertSame(['type' => 'string'], $result['items']);
    }

    public function testWithItemConstraints(): void
    {
        $result = ArrayField::create(StringField::create())
            ->withMinItems(1)
            ->withMaxItems(5)
            ->withUniqueItems()
            ->toArray();

        self::assertArrayHasKey('minItems', $result);
        self::assertArrayHasKey('maxItems', $result);
        self::assertArrayHasKey('uniqueItems', $result);
        self::assertSame(1, $result['minItems']);
        self::assertSame(5, $result['maxItems']);
        self::assertTrue($result['uniqueItems']);
    }

    public function testCreateWithDefault(): void
    {
        self::assertSame(['a', 'b'], ArrayField::create(StringField::create(), ['a', 'b'])->getDefault());
    }
}
