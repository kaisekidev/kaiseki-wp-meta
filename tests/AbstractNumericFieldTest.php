<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\NumberField;
use PHPUnit\Framework\TestCase;

/**
 * Exercises the shared numeric withers on
 * {@see \Kaiseki\WordPress\Meta\Field\AbstractNumericField}.
 */
final class AbstractNumericFieldTest extends TestCase
{
    public function testWithMinimumAndMaximum(): void
    {
        $result = IntegerField::create()
            ->withMinimum(1)
            ->withMaximum(10)
            ->toArray();

        self::assertArrayHasKey('minimum', $result);
        self::assertArrayHasKey('maximum', $result);
        self::assertSame(1, $result['minimum']);
        self::assertSame(10, $result['maximum']);
    }

    public function testWithExclusiveMinimumAndMaximum(): void
    {
        $result = NumberField::create()
            ->withExclusiveMinimum()
            ->withExclusiveMaximum()
            ->toArray();

        self::assertArrayHasKey('exclusiveMinimum', $result);
        self::assertArrayHasKey('exclusiveMaximum', $result);
        self::assertTrue($result['exclusiveMinimum']);
        self::assertTrue($result['exclusiveMaximum']);
    }

    public function testWithMultipleOf(): void
    {
        $result = IntegerField::create()->withMultipleOf(5)->toArray();

        self::assertArrayHasKey('multipleOf', $result);
        self::assertSame(5, $result['multipleOf']);
    }
}
