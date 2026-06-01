<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\NumberField;
use PHPUnit\Framework\TestCase;

final class NumberFieldTest extends TestCase
{
    public function testCreateWithDefault(): void
    {
        $field = NumberField::create(1.5);

        self::assertSame('number', $field->toArray()['type']);
        self::assertSame(1.5, $field->getDefault());
    }

    public function testCreateWithIntegerDefault(): void
    {
        $field = NumberField::create(3);

        self::assertSame('number', $field->toArray()['type']);
        self::assertSame(3, $field->getDefault());
    }

    public function testGetType(): void
    {
        self::assertSame('number', NumberField::create()->getType());
    }
}
