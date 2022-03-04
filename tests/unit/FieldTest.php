<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\StringField;
use PHPUnit\Framework\TestCase;

final class FieldTest extends TestCase
{
    /**
     * @dataProvider getFieldIsRequiredCases
     * @param callable(): FieldInterface $create
     * @param callable(FieldInterface): FieldInterface $modify
     */
    public function testFieldIsRequired(callable $create, callable $modify, bool $expected): void
    {
        $original = ($create)();

        $modified = ($modify)($original);

        self::assertSame($expected, $modified->isRequired());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable(FieldInterface): FieldInterface}>
     */
    public function getFieldIsRequiredCases(): iterable
    {
        yield 'with default is required' => [
            fn(): StringField => StringField::create('test'),
            fn(StringField $field): StringField => $field,
            true,
        ];
        yield 'without default is not required' => [
            fn(): StringField => StringField::create(),
            fn(StringField $field): StringField => $field,
            false,
        ];
        yield 'withOptionalValue is not required' => [
            fn(): StringField => StringField::create('test'),
            fn(StringField $field): StringField => $field->withOptionalValue(),
            false,
        ];
        yield 'withRequiredValue is required' => [
            fn(): StringField => StringField::create(),
            fn(StringField $field): StringField => $field->withRequiredValue(),
            true,
        ];
    }

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
    public function getFieldClonesCases(): iterable
    {
        yield 'withRequiredValue' => [
            fn(): StringField => StringField::create(),
            fn(StringField $field): StringField => $field->withRequiredValue(),
        ];
        yield 'withOptionalValue' => [
            fn(): StringField => StringField::create(),
            fn(StringField $field): StringField => $field->withOptionalValue(),
        ];
    }
}
