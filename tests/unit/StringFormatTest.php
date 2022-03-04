<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\StringFormat;
use PHPUnit\Framework\TestCase;

final class StringFormatTest extends TestCase
{
    public function testFromStringBuildsSameInstanceAsStaticMethod(): void
    {
        self::assertSame(StringFormat::uuid(), StringFormat::fromString('uuid'));
    }

    public function testThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        StringFormat::fromString('invalid');
    }

    /**
     * @dataProvider stringFormatHasExpectedValueCases
     * @param callable(): StringFormat $builder
     */
    public function testStringFormatHasExpectedValue(callable $builder, string $expectedValue): void
    {
        self::assertSame($expectedValue, (string)($builder)());
    }

    /**
     * @return iterable<string, array{callable(): StringFormat, string}>
     */
    public function stringFormatHasExpectedValueCases(): iterable
    {
        yield 'dateTime' => [fn(): StringFormat => StringFormat::dateTime(), 'date-time'];
        yield 'uri' => [fn(): StringFormat => StringFormat::uri(), 'uri'];
        yield 'email' => [fn(): StringFormat => StringFormat::email(), 'email'];
        yield 'hexColor' => [fn(): StringFormat => StringFormat::hexColor(), 'hex-color'];
        yield 'ip' => [fn(): StringFormat => StringFormat::ip(), 'ip'];
        yield 'uuid' => [fn(): StringFormat => StringFormat::uuid(), 'uuid'];
    }
}
