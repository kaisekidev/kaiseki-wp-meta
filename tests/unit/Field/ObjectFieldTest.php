<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\AbstractField;
use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\NumberField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;

final class ObjectFieldTest extends AbstractFieldTest
{
    /**
     * @inheritDoc
     */
    public function getDefaultIsExpectedValueCases(): iterable
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
     * @inheritDoc
     */
    public function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create(), 'object'];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultIsNullCases(): iterable
    {
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create()];
    }

    /**
     * @inheritDoc
     */
    public function getToArrayCases(): iterable
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
     * @inheritDoc
     */
    public function getFieldClonesCases(): iterable
    {
        yield 'withAddedProperty' => [
            static fn(): ObjectField => ObjectField::create(),
            fn(ObjectField $field): ObjectField => $field->withAddedProperty('name', StringField::create()),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getIsValidValueCases(): iterable
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
