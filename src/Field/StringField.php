<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

/**
 * @phpstan-type StringFieldArray array{
 *      type: string|array{string, string},
 *      default?: string,
 *      format?: string,
 *      pattern?: string,
 *      minLength?: int,
 *      maxLength?: int
 * }
 */
final class StringField implements FieldInterface
{
    private const TYPE_NAME = 'string';
    private ?string $default;
    private ?StringFormat $format = null;
    private ?string $pattern = null;
    private ?int $minLength = null;
    private ?int $maxLength = null;

    private function __construct(?string $default = null)
    {
        $this->default = $default;
    }

    public static function create(?StringFormat $format = null, ?string $default = null): self
    {
        $instance = new self($default);
        $instance->format = $format;
        return $instance;
    }

    public function withFormat(StringFormat $format): self
    {
        $clone = clone $this;
        $clone->format = $format;
        return $clone;
    }

    public function withPattern(string $pattern): self
    {
        $clone = clone $this;
        $clone->pattern = $pattern;
        return $clone;
    }

    public function withMinLength(int $minLength): self
    {
        $clone = clone $this;
        $clone->minLength = $minLength;
        return $clone;
    }

    public function withMaxLength(int $maxLength): self
    {
        $clone = clone $this;
        $clone->maxLength = $maxLength;
        return $clone;
    }

    /**
     * @phpstan-return StringFieldArray
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->default === null ? [self::TYPE_NAME, 'null'] : self::TYPE_NAME,
        ];
        if ($this->format !== null) {
            $array['format'] = (string)$this->format;
        }
        if ($this->pattern !== null) {
            $array['pattern'] = $this->pattern;
        }
        if ($this->minLength !== null) {
            $array['minLength'] = $this->minLength;
        }
        if ($this->maxLength !== null) {
            $array['maxLength'] = $this->maxLength;
        }
        return $array;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }
}
