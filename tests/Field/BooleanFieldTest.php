<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\FieldInterface;

final class BooleanFieldTest extends AbstractFieldTestCase
{
    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed}>
     */
    public static function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(false), false];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string}>
     */
    public static function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(true), 'boolean'];
    }

    /**
     * BooleanField always requires a non-null default, so the inherited
     * "default is null" expectation does not apply.
     *
     * @return iterable<string, array{callable(): FieldInterface}>
     */
    public static function getDefaultIsNullCases(): iterable
    {
        yield 'BooleanField is never null' => [fn(): BooleanField => BooleanField::create(false)];
    }

    /**
     * @dataProvider getDefaultIsNullCases
     *
     * @param callable(): FieldInterface $builder
     */
    public function testGetDefaultIsNull(callable $builder): void
    {
        self::assertFalse(($builder)()->getDefault());
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, string, mixed}>
     */
    public static function getToArrayCases(): iterable
    {
        yield 'boolean type' => [
            fn(): BooleanField => BooleanField::create(true),
            'type',
            'boolean',
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, callable}>
     */
    public static function getFieldClonesCases(): iterable
    {
        yield 'withRequiredValue clones' => [
            static fn(): BooleanField => BooleanField::create(true),
            fn(BooleanField $field): BooleanField => $field->withRequiredValue(),
        ];
    }

    /**
     * @return iterable<string, array{callable(): FieldInterface, mixed, bool}>
     */
    public static function getIsValidValueCases(): iterable
    {
        yield 'valid true' => [fn(): BooleanField => BooleanField::create(true), false, true];
    }
}
