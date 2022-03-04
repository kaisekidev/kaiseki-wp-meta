<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;

final class StringFieldTest extends AbstractFieldTest
{
    /**
     * @inheritDoc
     */
    public function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create('foo'), 'foo'];
    }

    /**
     * @inheritDoc
     */
    public function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create(), 'string'];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultIsNullCases(): iterable
    {
        yield 'StringField' => [fn(): StringField => StringField::create()];
    }

    /**
     * @inheritDoc
     */
    public function getToArrayCases(): iterable
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
     * @inheritDoc
     */
    public function getFieldClonesCases(): iterable
    {
        $create = static fn(): StringField => StringField::create();
        yield 'withPattern' => [$create, fn(StringField $field): StringField => $field->withPattern('^[a-z]{1,5}$')];
        yield 'withFormat' => [$create, fn(StringField $field): StringField => $field->withFormat(StringFormat::ip())];
        yield 'withMinLength' => [$create, fn(StringField $field): StringField => $field->withMinLength(3)];
        yield 'withMaxLength' => [$create, fn(StringField $field): StringField => $field->withMaxLength(10)];
    }

    /**
     * @inheritDoc
     */
    public function getIsValidValueCases(): iterable
    {
        yield 'valid string' => [fn(): StringField => StringField::create(), 'foo', true];
        yield 'invalid string' => [fn(): StringField => StringField::create(), 1, false];
    }
}
