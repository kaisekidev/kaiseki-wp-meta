<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\StringFormat;
use PHPUnit\Framework\TestCase;
use ValueError;

final class StringFormatTest extends TestCase
{
    public function testFromBuildsSameInstanceAsCase(): void
    {
        self::assertSame(StringFormat::Uuid, StringFormat::from('uuid'));
    }

    public function testThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(ValueError::class);

        StringFormat::from('invalid');
    }

    /**
     * @dataProvider stringFormatHasExpectedValueCases
     *
     * @param StringFormat $format
     * @param string       $expectedValue
     */
    public function testStringFormatHasExpectedValue(StringFormat $format, string $expectedValue): void
    {
        self::assertSame($expectedValue, $format->value);
    }

    /**
     * @return iterable<string, array{StringFormat, string}>
     */
    public static function stringFormatHasExpectedValueCases(): iterable
    {
        yield 'dateTime' => [StringFormat::DateTime, 'date-time'];
        yield 'uri' => [StringFormat::Uri, 'uri'];
        yield 'email' => [StringFormat::Email, 'email'];
        yield 'hexColor' => [StringFormat::HexColor, 'hex-color'];
        yield 'ip' => [StringFormat::Ip, 'ip'];
        yield 'uuid' => [StringFormat::Uuid, 'uuid'];
    }
}
