<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use InvalidArgumentException;
use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\IntegerField;

final class ArrayFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create(), [1, 2, 3]), [1, 2, 3]];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), 'array'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create())];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
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
            IntegerField::create()->withRequiredValue()->toArray(),
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
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        $create = static fn(): ArrayField => ArrayField::create(IntegerField::create());
        yield 'withUniqueItems' => [$create, fn(ArrayField $field): ArrayField => $field->withUniqueItems()];
        yield 'withMaxItems' => [$create, fn(ArrayField $field): ArrayField => $field->withMaxItems(5)];
        yield 'withMinItems' => [$create, fn(ArrayField $field): ArrayField => $field->withMinItems(2)];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'invalid not array' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), 1, false];
        yield 'valid integer' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), [1], true];
        yield 'invalid integer' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), [1, 1.0], false];
    }

    public function testThrowsExceptionWithInvalidDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ArrayField expects an array of integer, but contains string');

        ArrayField::create(IntegerField::create(), [1, '1']);
    }
}
