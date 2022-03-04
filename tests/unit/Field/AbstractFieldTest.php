<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\FieldInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractFieldTest extends TestCase
{
    /**
     * @dataProvider getDefaultIsExpectedValueCases
     * @param callable(): FieldInterface $builder
     * @param mixed $expectedValue
     */
    public function testGetDefaultIsExpectedValue(callable $builder, $expectedValue): void
    {
        $default = ($builder)()->getDefault();

        self::assertSame($expectedValue, $default);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    abstract public function getDefaultIsExpectedValueCases(): iterable;

    /**
     * @dataProvider getTypeIsExpectedTypeCases
     * @param callable(): FieldInterface $builder
     */
    public function testGetTypeIsExpectedType(callable $builder, string $expectedType): void
    {
        self::assertSame($expectedType, ($builder)()->getType());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    abstract public function getTypeIsExpectedTypeCases(): iterable;

    /**
     * @dataProvider GetDefaultIsNullCases
     * @param callable(): FieldInterface $builder
     */
    public function testGetDefaultIsNull(callable $builder): void
    {
        self::assertNull(($builder)()->getDefault());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    abstract public function getDefaultIsNullCases(): iterable;

    /**
     * @dataProvider getToArrayCases
     * @param callable(): FieldInterface $builder
     * @param mixed $expectedValue
     */
    public function testToArray(callable $builder, string $expectedArrayKey, $expectedValue): void
    {
        $array = ($builder)()->toArray();

        self::assertArrayHasKey($expectedArrayKey, $array);
        self::assertSame($expectedValue, $array[$expectedArrayKey]);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    abstract public function getToArrayCases(): iterable;

    /**
     * @dataProvider getFieldClonesCases
     * @param callable(): FieldInterface $create
     * @param callable(FieldInterface): FieldInterface $modify
     */
    public function testFieldClones(callable $create, callable $modify): void
    {
        $original = ($create)();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable(FieldInterface): FieldInterface}>
     */
    abstract public function getFieldClonesCases(): iterable;

    /**
     * @dataProvider getIsValidValueCases
     * @param callable(): FieldInterface $create
     * @param mixed $value
     */
    public function testIsValidValue(callable $create, $value, bool $expected): void
    {
        $instance = ($create)();

        self::assertSame($expected, $instance->isValidValue($value));
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    abstract public function getIsValidValueCases(): iterable;
}
