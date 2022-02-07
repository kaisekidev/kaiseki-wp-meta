<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\NumberField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use PHPUnit\Framework\TestCase;

final class FieldTest extends TestCase
{
    /**
     * @dataProvider getDefaultCasesIsExpectedValueCases
     * @param callable(): FieldInterface $builder
     * @param mixed $expectedValue
     */
    public function testGetDefaultCasesIsExpectedValue(callable $builder, $expectedValue): void
    {
        $default = ($builder)()->getDefault();

        self::assertSame($expectedValue, $default);
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public function getDefaultCasesIsExpectedValueCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create(), [1, 2, 3]), [1, 2, 3]];
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(false), false];
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create(2), 2];
        yield 'NumberField' => [fn(): NumberField => NumberField::create(2.0), 2.0];
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
        yield 'StringField' => [fn(): StringField => StringField::create('foo'), 'foo'];
    }

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
    public function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), 'array'];
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(true), 'boolean'];
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create(), 'integer'];
        yield 'NumberField' => [fn(): NumberField => NumberField::create(), 'number'];
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create(), 'object'];
        yield 'StringField' => [fn(): StringField => StringField::create(), 'string'];
    }

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
    public function getDefaultIsNullCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create())];
        yield 'IntegerField' => [fn(): IntegerField => IntegerField::create()];
        yield 'NumberField' => [fn(): NumberField => NumberField::create()];
        yield 'ObjectField' => [fn(): ObjectField => ObjectField::create()];
        yield 'StringField' => [fn(): StringField => StringField::create()];
    }

    /**
     * @dataProvider toArrayCases
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
    public function toArrayCases(): iterable
    {
        yield 'array type with default' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create(), [1, 2, 3]),
            'type',
            'array',
        ];
        yield 'array type without default' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create()),
            'type',
            ['array', 'null'],
        ];
        yield 'array items' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create()),
            'items',
            IntegerField::create()->toArray(),
        ];
        yield 'array minItems' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create())->withMinItems(1),
            'minItems',
            1,
        ];
        yield 'array maxItems' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create())->withMaxItems(4),
            'maxItems',
            4,
        ];
        yield 'array uniqueItems' => [
            fn(): ArrayField => ArrayField::create(IntegerField::create())->withUniqueItems(),
            'uniqueItems',
            true,
        ];
        yield 'boolean type' => [
            fn(): BooleanField => BooleanField::create(true),
            'type',
            'boolean',
        ];
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
            fn(): IntegerField => IntegerField::create()->withMinimum(1),
            'minimum',
            1,
        ];
        yield 'integer exclusiveMinimum' => [
            fn(): IntegerField => IntegerField::create()->withExcludedMinimum(),
            'exclusiveMinimum',
            true,
        ];
        yield 'integer maximum' => [
            fn(): IntegerField => IntegerField::create()->withMaximum(4),
            'maximum',
            4,
        ];
        yield 'integer exclusiveMaximum' => [
            fn(): IntegerField => IntegerField::create()->withExcludedMaximum(),
            'exclusiveMaximum',
            true,
        ];
        yield 'integer multipleOf' => [
            fn(): IntegerField => IntegerField::create()->withMultipleOf(2),
            'multipleOf',
            2,
        ];
        yield 'number type with default' => [
            fn(): NumberField => NumberField::create(1),
            'type',
            'number',
        ];
        yield 'number type without default' => [
            fn(): NumberField => NumberField::create(),
            'type',
            ['number', 'null'],
        ];
        yield 'number minimum' => [
            fn(): NumberField => NumberField::create()->withMinimum(1),
            'minimum',
            1.0,
        ];
        yield 'number exclusiveMinimum' => [
            fn(): NumberField => NumberField::create()->withExcludedMinimum(),
            'exclusiveMinimum',
            true,
        ];
        yield 'number maximum' => [
            fn(): NumberField => NumberField::create()->withMaximum(4),
            'maximum',
            4.0,
        ];
        yield 'number exclusiveMaximum' => [
            fn(): NumberField => NumberField::create()->withExcludedMaximum(),
            'exclusiveMaximum',
            true,
        ];
        yield 'number multipleOf' => [
            fn(): NumberField => NumberField::create()->withMultipleOf(2),
            'multipleOf',
            2.0,
        ];
        yield 'object type' => [
            fn(): ObjectField => ObjectField::create(),
            'type',
            'object',
        ];
        yield 'object type withNullAllowed' => [
            fn(): ObjectField => ObjectField::create()->withNullAllowed(),
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
        yield 'string type with default' => [
            fn(): StringField => StringField::create('foo'),
            'type',
            'string',
        ];
        yield 'string type without default' => [
            fn(): StringField => StringField::create(),
            'type',
            ['string', 'null'],
        ];
        yield 'string format' => [
            fn(): StringField => StringField::create()->withFormat(StringFormat::dateTime()),
            'format',
            (string)StringFormat::dateTime(),
        ];
        yield 'string maxLength' => [
            fn(): StringField => StringField::create()->withMaxLength(10),
            'maxLength',
            10,
        ];
        yield 'string minLength' => [
            fn(): StringField => StringField::create()->withMinLength(3),
            'minLength',
            3,
        ];
        yield 'string pattern' => [
            fn(): StringField => StringField::create()->withPattern('^[a-z]+$'),
            'pattern',
            '^[a-z]+$',
        ];
    }

    /**
     * @dataProvider arrayFieldClonesCases
     * @param callable(ArrayField): ArrayField $modify
     */
    public function testArrayFieldClones(callable $modify): void
    {
        $original = ArrayField::create(IntegerField::create());

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(ArrayField): ArrayField}>
     */
    public function arrayFieldClonesCases(): iterable
    {
        yield 'withUniqueItems' => [fn(ArrayField $field): ArrayField => $field->withUniqueItems()];
        yield 'withMaxItems' => [fn(ArrayField $field): ArrayField => $field->withMaxItems(5)];
        yield 'withMinItems' => [fn(ArrayField $field): ArrayField => $field->withMinItems(2)];
    }

    /**
     * @dataProvider objectFieldClonesCases
     * @param callable(ObjectField): ObjectField $modify
     */
    public function testObjectFieldClones(callable $modify): void
    {
        $original = ObjectField::create();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(ObjectField): ObjectField}>
     */
    public function objectFieldClonesCases(): iterable
    {
        yield 'withAddedProperty' => [
            fn(ObjectField $field): ObjectField => $field->withAddedProperty('name', StringField::create()),
        ];
    }

    /**
     * @dataProvider integerFieldClonesCases
     * @param callable(IntegerField): IntegerField $modify
     */
    public function testIntegerFieldClones(callable $modify): void
    {
        $original = IntegerField::create();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(IntegerField): IntegerField}>
     */
    public function integerFieldClonesCases(): iterable
    {
        yield 'withMinimum' => [fn(IntegerField $field): IntegerField => $field->withMinimum(1)];
        yield 'withExcludedMinimum' => [fn(IntegerField $field): IntegerField => $field->withExcludedMinimum()];
        yield 'withMaximum' => [fn(IntegerField $field): IntegerField => $field->withMaximum(10)];
        yield 'withExcludedMaximum' => [fn(IntegerField $field): IntegerField => $field->withExcludedMaximum()];
        yield 'withMultipleOf' => [fn(IntegerField $field): IntegerField => $field->withMultipleOf(2)];
    }

    /**
     * @dataProvider numberFieldClonesCases
     * @param callable(NumberField): NumberField $modify
     */
    public function testNumberFieldClones(callable $modify): void
    {
        $original = NumberField::create();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(NumberField): NumberField}>
     */
    public function numberFieldClonesCases(): iterable
    {
        yield 'withMinimum' => [fn(NumberField $field): NumberField => $field->withMinimum(1)];
        yield 'withExcludedMinimum' => [fn(NumberField $field): NumberField => $field->withExcludedMinimum()];
        yield 'withMaximum' => [fn(NumberField $field): NumberField => $field->withMaximum(10)];
        yield 'withExcludedMaximum' => [fn(NumberField $field): NumberField => $field->withExcludedMaximum()];
        yield 'withMultipleOf' => [fn(NumberField $field): NumberField => $field->withMultipleOf(2)];
    }

    /**
     * @dataProvider stringFieldClonesCases
     * @param callable(StringField): StringField $modify
     */
    public function testStringFieldClones(callable $modify): void
    {
        $original = StringField::create();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(StringField): StringField}>
     */
    public function stringFieldClonesCases(): iterable
    {
        yield 'withPattern' => [fn(StringField $field): StringField => $field->withPattern('^[a-z]{1,5}$')];
        yield 'withFormat' => [fn(StringField $field): StringField => $field->withFormat(StringFormat::ip())];
        yield 'withMinLength' => [fn(StringField $field): StringField => $field->withMinLength(3)];
        yield 'withMaxLength' => [fn(StringField $field): StringField => $field->withMaxLength(10)];
    }

    public function testObjectWithAddedPropertyIsNotRequiredByDefault(): void
    {
        $field = ObjectField::create()->withAddedProperty('name', StringField::create());

        $array = $field->toArray();

        self::assertArrayNotHasKey('required', $array);
    }
}
