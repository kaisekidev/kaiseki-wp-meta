<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\AbstractNumericField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\IntegerField;

final class IntegerFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create(2), 2];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create(), 'integer'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create()];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
    {
        yield 'integer type with default' => [
            fn(): IntegerField => IntegerField::create(1),
            'type',
            'integer',
        ];
        yield 'integer type without default' => [
            fn(): IntegerField => IntegerField::create(),
            'type',
            ['integer', 'null'],
        ];
        yield 'integer minimum' => [
            fn(): AbstractNumericField => IntegerField::create()->withMinimum(1),
            'minimum',
            1,
        ];
        yield 'integer exclusiveMinimum' => [
            fn(): AbstractNumericField => IntegerField::create()->withExcludedMinimum(),
            'exclusiveMinimum',
            true,
        ];
        yield 'integer maximum' => [
            fn(): AbstractNumericField => IntegerField::create()->withMaximum(4),
            'maximum',
            4,
        ];
        yield 'integer exclusiveMaximum' => [
            fn(): AbstractNumericField => IntegerField::create()->withExcludedMaximum(),
            'exclusiveMaximum',
            true,
        ];
        yield 'integer multipleOf' => [
            fn(): AbstractNumericField => IntegerField::create()->withMultipleOf(2),
            'multipleOf',
            2,
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        $create = static fn(): IntegerField => IntegerField::create();
        yield 'withMinimum' => [$create, fn(IntegerField $field): IntegerField => $field->withMinimum(1)];
        yield 'withExcludedMinimum' => [$create, fn(IntegerField $field): IntegerField => $field->withExcludedMinimum()];
        yield 'withMaximum' => [$create, fn(IntegerField $field): IntegerField => $field->withMaximum(10)];
        yield 'withExcludedMaximum' => [$create, fn(IntegerField $field): IntegerField => $field->withExcludedMaximum()];
        yield 'withMultipleOf' => [$create, fn(IntegerField $field): IntegerField => $field->withMultipleOf(2)];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'valid integer' => [fn(): IntegerField => IntegerField::create(), 1, true];
        yield 'invalid integer' => [fn(): IntegerField => IntegerField::create(), '1', false];
    }
}
