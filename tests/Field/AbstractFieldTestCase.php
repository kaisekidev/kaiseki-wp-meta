<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\FieldInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractFieldTestCase extends TestCase
{
    /**
     * @dataProvider getDefaultIsExpectedValueCases
     *
     * @param callable(): FieldInterface $builder
     * @param mixed                      $expectedValue
     */
    public function testGetDefaultIsExpectedValue(callable $builder, mixed $expectedValue): void
    {
        $default = ($builder)()->getDefault();

        self::assertSame($expectedValue, $default);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    abstract public static function getDefaultIsExpectedValueCases(): iterable;

    /**
     * @dataProvider getTypeIsExpectedTypeCases
     *
     * @param callable(): FieldInterface $builder
     * @param string                     $expectedType
     */
    public function testGetTypeIsExpectedType(callable $builder, string $expectedType): void
    {
        self::assertSame($expectedType, ($builder)()->getType());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    abstract public static function getTypeIsExpectedTypeCases(): iterable;

    /**
     * @dataProvider getDefaultIsNullCases
     *
     * @param callable(): FieldInterface $builder
     */
    public function testGetDefaultIsNull(callable $builder): void
    {
        self::assertNull(($builder)()->getDefault());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    abstract public static function getDefaultIsNullCases(): iterable;

    /**
     * @dataProvider getToArrayCases
     *
     * @param callable(): FieldInterface $builder
     * @param string                     $expectedArrayKey
     * @param mixed                      $expectedValue
     */
    public function testToArray(callable $builder, string $expectedArrayKey, mixed $expectedValue): void
    {
        $array = ($builder)()->toArray();

        self::assertArrayHasKey($expectedArrayKey, $array);
        self::assertSame($expectedValue, $array[$expectedArrayKey]);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    abstract public static function getToArrayCases(): iterable;

    /**
     * @dataProvider getFieldClonesCases
     *
     * @param callable(): FieldInterface               $create
     * @param callable(FieldInterface): FieldInterface $modify
     */
    public function testFieldClones(callable $create, callable $modify): void
    {
        $original = ($create)();

        /** @var FieldInterface $modified */
        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    abstract public static function getFieldClonesCases(): iterable;

    /**
     * @dataProvider getIsValidValueCases
     *
     * @param callable(): FieldInterface $create
     * @param mixed                      $value
     * @param bool                       $expected
     */
    public function testIsValidValue(callable $create, mixed $value, bool $expected): void
    {
        $instance = ($create)();

        self::assertSame($expected, $instance->isValidValue($value));
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    abstract public static function getIsValidValueCases(): iterable;
}
