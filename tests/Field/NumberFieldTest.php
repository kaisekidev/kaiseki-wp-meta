<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\AbstractNumericField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\NumberField;

final class NumberFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'NumberField' => [fn(): NumberField => NumberField::create(2.0), 2.0];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'NumberField' => [fn(): NumberField => NumberField::create(), 'number'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'NumberField' => [fn(): NumberField => NumberField::create()];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
    {
        yield 'number minimum' => [
            fn(): AbstractNumericField => NumberField::create()->withMinimum(1.0),
            'minimum',
            1.0,
        ];
        yield 'number exclusiveMinimum' => [
            fn(): AbstractNumericField => NumberField::create()->withExcludedMinimum(),
            'exclusiveMinimum',
            true,
        ];
        yield 'number maximum' => [
            fn(): AbstractNumericField => NumberField::create()->withMaximum(4.0),
            'maximum',
            4.0,
        ];
        yield 'number exclusiveMaximum' => [
            fn(): AbstractNumericField => NumberField::create()->withExcludedMaximum(),
            'exclusiveMaximum',
            true,
        ];
        yield 'number multipleOf' => [
            fn(): AbstractNumericField => NumberField::create()->withMultipleOf(2.0),
            'multipleOf',
            2.0,
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        $create = static fn(): NumberField => NumberField::create();
        yield 'withMinimum' => [$create, fn(NumberField $field): NumberField => $field->withMinimum(1.0)];
        yield 'withExcludedMinimum' => [$create, fn(NumberField $field): NumberField => $field->withExcludedMinimum()];
        yield 'withMaximum' => [$create, fn(NumberField $field): NumberField => $field->withMaximum(10.0)];
        yield 'withExcludedMaximum' => [$create, fn(NumberField $field): NumberField => $field->withExcludedMaximum()];
        yield 'withMultipleOf' => [$create, fn(NumberField $field): NumberField => $field->withMultipleOf(2.0)];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'valid number' => [fn(): NumberField => NumberField::create(), 1.0, true];
        yield 'invalid number' => [fn(): NumberField => NumberField::create(), 1, false];
    }
}
