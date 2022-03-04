<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\Field;

use Kaiseki\WordPress\Meta\Field\BooleanField;

final class BooleanFieldTest extends AbstractFieldTest
{
    /**
     * @inheritDoc
     */
    public function getDefaultIsExpectedValueCases(): iterable
    {
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(false), false];
    }

    /**
     * @inheritDoc
     */
    public function getTypeIsExpectedTypeCases(): iterable
    {
        yield 'BooleanField' => [fn(): BooleanField => BooleanField::create(true), 'boolean'];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultIsNullCases(): iterable
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getToArrayCases(): iterable
    {
        yield 'boolean type' => [
            fn(): BooleanField => BooleanField::create(true),
            'type',
            'boolean',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFieldClonesCases(): iterable
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getIsValidValueCases(): iterable
    {
        yield 'valid true' => [fn(): BooleanField => BooleanField::create(true), false, true];
    }
}
