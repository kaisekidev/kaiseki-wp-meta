<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use PHPUnit\Framework\TestCase;

final class StringFieldTest extends TestCase
{
    public function testCreateWithoutDefault(): void
    {
        $field = StringField::create();

        self::assertSame('string', $field->toArray()['type']);
        self::assertNull($field->getDefault());
    }

    public function testCreateWithDefault(): void
    {
        $field = StringField::create('hello');

        self::assertSame('string', $field->toArray()['type']);
        self::assertSame('hello', $field->getDefault());
    }

    public function testGetType(): void
    {
        self::assertSame('string', StringField::create()->getType());
    }

    public function testWithFormat(): void
    {
        $result = StringField::create()->withFormat(StringFormat::Email)->toArray();

        self::assertArrayHasKey('format', $result);
        self::assertSame('email', $result['format']);
    }

    public function testWithPattern(): void
    {
        $result = StringField::create()->withPattern('^[a-z]+$')->toArray();

        self::assertArrayHasKey('pattern', $result);
        self::assertSame('^[a-z]+$', $result['pattern']);
    }

    public function testWithMinAndMaxLength(): void
    {
        $result = StringField::create()
            ->withMinLength(2)
            ->withMaxLength(10)
            ->toArray();

        self::assertArrayHasKey('minLength', $result);
        self::assertArrayHasKey('maxLength', $result);
        self::assertSame(2, $result['minLength']);
        self::assertSame(10, $result['maxLength']);
    }
}
