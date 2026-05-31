<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\StringField;
use PHPUnit\Framework\TestCase;

final class FieldTest extends TestCase
{
    /**
     * @dataProvider getFieldIsRequiredCases
     *
     * @param callable(): StringField            $create
     * @param callable(StringField): StringField $modify
     * @param bool                               $expected
     */
    public function testFieldIsRequired(callable $create, callable $modify, bool $expected): void
    {
        $original = ($create)();

        $modified = ($modify)($original);

        self::assertSame($expected, $modified->isRequired());
    }

    /**
     * @return iterable<string, array{callable(): StringField, callable(StringField): StringField, bool}>
     */
    public static function getFieldIsRequiredCases(): iterable
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
     *
     * @param callable(): StringField            $create
     * @param callable(StringField): StringField $modify
     */
    public function testFieldClones(callable $create, callable $modify): void
    {
        $original = ($create)();

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(): StringField, callable(StringField): StringField}>
     */
    public static function getFieldClonesCases(): iterable
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
