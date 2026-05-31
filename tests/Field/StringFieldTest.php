<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\FieldInterface;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;

final class StringFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create('foo'), 'foo'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create(), 'string'];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create()];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
    {
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
            fn(): StringField => StringField::create()->withFormat(StringFormat::DateTime),
            'format',
            StringFormat::DateTime->value,
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
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        $create = static fn(): StringField => StringField::create();
        yield 'withPattern' => [$create, fn(StringField $field): StringField => $field->withPattern('^[a-z]{1,5}$')];
        yield 'withFormat' => [$create, fn(StringField $field): StringField => $field->withFormat(StringFormat::Ip)];
        yield 'withMinLength' => [$create, fn(StringField $field): StringField => $field->withMinLength(3)];
        yield 'withMaxLength' => [$create, fn(StringField $field): StringField => $field->withMaxLength(10)];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'valid string' => [fn(): StringField => StringField::create(), 'foo', true];
        yield 'invalid string' => [fn(): StringField => StringField::create(), 1, false];
    }
}
