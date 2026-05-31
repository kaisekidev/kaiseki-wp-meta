<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\AbstractField;
use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\NumberField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;

final class ObjectFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'ObjectField' => [
            fn(): ObjectField => ObjectField::create(
                [
                    'foo' => StringField::create('bar'),
                    'baz' => BooleanField::create(true),
                    'array' => ArrayField::create(StringField::create(), ['a', 'b', 'c']),
                    'integer' => IntegerField::create(1),
                    'number' => NumberField::create(1.0),
                ]
            ), ['foo' => 'bar', 'baz' => true, 'array' => ['a', 'b', 'c'], 'integer' => 1, 'number' => 1.0],
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create(), 'object'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create()];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
    {
        yield 'object type' => [
            fn(): AbstractField => ObjectField::create()->withRequiredValue(),
            'type',
            'object',
        ];
        yield 'object type withNullAllowed' => [
            fn(): ObjectField => ObjectField::create(),
            'type',
            ['object', 'null'],
        ];
        yield 'object withProperty' => [
            fn(): ObjectField => ObjectField::create()->withAddedProperty('foo', StringField::create()),
            'properties',
            ['foo' => ['type' => ['string', 'null']]],
        ];
        yield 'object withProperty required' => [
            fn(): ObjectField => ObjectField::create()->withAddedProperty('foo', StringField::create(), true),
            'required',
            ['foo'],
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        yield 'withAddedProperty' => [
            static fn(): ObjectField => ObjectField::create(),
            fn(ObjectField $field): ObjectField => $field->withAddedProperty('name', StringField::create()),
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'valid object' => [
            fn(): ObjectField => ObjectField::create(['foo' => StringField::create()]),
            ['foo' => 'bar'],
            true,
        ];
        yield 'invalid object no array' => [
            fn(): ObjectField => ObjectField::create(['foo' => StringField::create()]),
            'foo',
            false,
        ];
        yield 'invalid object wrong value' => [
            fn(): ObjectField => ObjectField::create(['foo' => StringField::create()]),
            ['foo' => 1.0],
            false,
        ];
        yield 'invalid object missing property' => [
            fn(): ObjectField => ObjectField::create(['foo' => StringField::create()]),
            ['foo' => 'bar', 'bar' => 'bar'],
            false,
        ];
    }
}
