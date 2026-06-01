<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\IntegerField;
use PHPUnit\Framework\TestCase;

final class IntegerFieldTest extends TestCase
{
    public function testCreateWithDefault(): void
    {
        $field = IntegerField::create(42);

        self::assertSame('integer', $field->toArray()['type']);
        self::assertSame(42, $field->getDefault());
    }

    public function testCreateWithoutDefault(): void
    {
        $field = IntegerField::create();

        self::assertSame('integer', $field->toArray()['type']);
        self::assertNull($field->getDefault());
    }

    public function testGetType(): void
    {
        self::assertSame('integer', IntegerField::create()->getType());
    }
}
