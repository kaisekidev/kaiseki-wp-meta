<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use InvalidArgumentException;

use function in_array;

final class StringFormat
{
    /** @var array<string, self> */
    private static array $pool;
    private const FORMAT_DATE_TIME = 'date-time';
    private const FORMAT_URI = 'uri';
    private const FORMAT_EMAIL = 'email';
    private const FORMAT_IP = 'ip';
    private const FORMAT_UUID = 'uuid';
    private const FORMAT_HEX_COLOR = 'hex-color';
    private const ALLOWED_FORMATS = [
        self::FORMAT_DATE_TIME,
        self::FORMAT_URI,
        self::FORMAT_EMAIL,
        self::FORMAT_IP,
        self::FORMAT_UUID,
        self::FORMAT_HEX_COLOR,
    ];
    private string $format;

    private function __construct(string $format)
    {
        $this->format = $format;
    }

    public static function fromString(string $format): self
    {
        if (!in_array($format, self::ALLOWED_FORMATS, true)) {
            throw new InvalidArgumentException(\Safe\sprintf('"%s" is not a valid format', $format));
        }
        return self::get($format);
    }

    public static function dateTime(): self
    {
        return self::get(self::FORMAT_DATE_TIME);
    }

    public static function uri(): self
    {
        return self::get(self::FORMAT_URI);
    }

    public static function email(): self
    {
        return self::get(self::FORMAT_EMAIL);
    }

    public static function ip(): self
    {
        return self::get(self::FORMAT_IP);
    }

    public static function uuid(): self
    {
        return self::get(self::FORMAT_UUID);
    }

    public static function hexColor(): self
    {
        return self::get(self::FORMAT_HEX_COLOR);
    }

    private static function get(string $format): self
    {
        if (!isset(self::$pool[$format])) {
            self::$pool[$format] = new self($format);
        }
        return self::$pool[$format];
    }

    public function __toString(): string
    {
        return $this->format;
    }
}
