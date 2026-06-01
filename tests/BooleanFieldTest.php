<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\BooleanField;
use PHPUnit\Framework\TestCase;

final class BooleanFieldTest extends TestCase
{
    public function testCreateWithDefault(): void
    {
        $field = BooleanField::create(true);

        self::assertSame('boolean', $field->toArray()['type']);
        self::assertTrue($field->getDefault());
    }

    public function testCreateWithoutDefault(): void
    {
        $field = BooleanField::create();

        self::assertSame('boolean', $field->toArray()['type']);
        self::assertNull($field->getDefault());
    }

    public function testGetType(): void
    {
        self::assertSame('boolean', BooleanField::create()->getType());
    }
}
