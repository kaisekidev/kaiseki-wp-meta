<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\IntegerField;

final class ArrayFieldTest extends AbstractFieldTest
{
    /**
     * @inheritDoc
     */
    public function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create(), [1, 2, 3]), [1, 2, 3]];
    }

    /**
     * @inheritDoc
     */
    public function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), 'array'];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultIsNullCases(): iterable
    {
        yield 'ArrayField' => [fn(): ArrayField => ArrayField::create(IntegerField::create())];
    }

    /**
     * @inheritDoc
     */
    public function getToArrayCases(): iterable
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
     * @inheritDoc
     */
    public function getFieldClonesCases(): iterable
    {
        $create = static fn(): ArrayField => ArrayField::create(IntegerField::create());
        yield 'withUniqueItems' => [$create, fn(ArrayField $field): ArrayField => $field->withUniqueItems()];
        yield 'withMaxItems' => [$create, fn(ArrayField $field): ArrayField => $field->withMaxItems(5)];
        yield 'withMinItems' => [$create, fn(ArrayField $field): ArrayField => $field->withMinItems(2)];
    }

    /**
     * @inheritDoc
     */
    public function getIsValidValueCases(): iterable
    {
        yield 'invalid not array' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), 1, false];
        yield 'valid integer' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), [1], true];
        yield 'invalid integer' => [fn(): ArrayField => ArrayField::create(IntegerField::create()), [1, 1.0], false];
    }

    public function testThrowsExceptionWithInvalidDefault(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ArrayField expects an array of integer, but contains string');

        ArrayField::create(IntegerField::create(), [1, '1']);
    }
}
