<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

use function is_string;

/**
 * @phpstan-type StringFieldArray array{
 *      type: string|array{string, string},
 *      format?: string,
 *      pattern?: string,
 *      minLength?: int,
 *      maxLength?: int
 * }
 * @extends AbstractField<string>
 */
final class StringField extends AbstractField
{
    private const TYPE_NAME = 'string';
    private ?StringFormat $format = null;
    private ?string $pattern = null;
    private ?int $minLength = null;
    private ?int $maxLength = null;

    public static function create(?string $default = null): self
    {
        return new self($default);
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
        $array = parent::toArray();
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

    public function getType(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param mixed $value
     */
    public function isValidValue($value): bool
    {
        return is_string($value);
    }
}
