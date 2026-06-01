<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\FieldInterface;

final class MetaData implements MetaDataInterface
{
    private const OBJECT_TYPE_POST = 'post';
    private const OBJECT_TYPE_TERM = 'term';
    private const OBJECT_TYPE_USER = 'user';
    private const OBJECT_TYPE_COMMENT = 'comment';

    private bool $showInRest = false;
    private bool $isSingle = true;
    private ?string $description = null;
    /** @var (callable(): bool)|null */
    private $authCallback = null;
    /** @var (callable(mixed): mixed)|null */
    private $sanitizeCallback = null;

    private function __construct(
        private readonly string $objectType,
        private readonly string $objectSubType,
        private readonly string $metaKey,
        private readonly FieldInterface $field,
    ) {
    }

    public static function post(string $postType, string $metaKey, FieldInterface $field): self
    {
        return new self(self::OBJECT_TYPE_POST, $postType, $metaKey, $field);
    }

    public static function term(string $taxonomy, string $metaKey, FieldInterface $field): self
    {
        return new self(self::OBJECT_TYPE_TERM, $taxonomy, $metaKey, $field);
    }

    public static function user(string $metaKey, FieldInterface $field): self
    {
        return new self(self::OBJECT_TYPE_USER, '', $metaKey, $field);
    }

    public static function comment(string $metaKey, FieldInterface $field): self
    {
        return new self(self::OBJECT_TYPE_COMMENT, '', $metaKey, $field);
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getMetaKey(): string
    {
        return $this->metaKey;
    }

    public function withShowInRest(): self
    {
        $clone = clone $this;
        $clone->showInRest = true;

        return $clone;
    }

    public function withMultipleValue(): self
    {
        $clone = clone $this;
        $clone->isSingle = false;

        return $clone;
    }

    public function withDescription(string $description): self
    {
        $clone = clone $this;
        $clone->description = $description;

        return $clone;
    }

    /**
     * @param callable(): bool $callable
     */
    public function withAuthCallback(callable $callable): self
    {
        $clone = clone $this;
        $clone->authCallback = $callable;

        return $clone;
    }

    /**
     * Opt in to write-time sanitization. By default no `sanitize_callback` is
     * registered — REST writes are still validated against the generated
     * `show_in_rest` schema.
     *
     * @param callable(mixed): mixed $callable
     */
    public function withSanitizeCallback(callable $callable): self
    {
        $clone = clone $this;
        $clone->sanitizeCallback = $callable;

        return $clone;
    }

    /**
     * @return array{
     *      single: bool,
     *      default: mixed,
     *      type: string,
     *      object_subtype?: string,
     *      description?: string,
     *      show_in_rest?: array<string, mixed>,
     *      auth_callback?: callable(): bool,
     *      sanitize_callback?: callable(mixed): mixed
     * }
     */
    public function toArray(): array
    {
        $array = [
            'single' => $this->isSingle,
            'default' => $this->field->getDefault(),
            'type' => $this->field->getType(),
        ];
        if ($this->objectSubType !== '') {
            $array['object_subtype'] = $this->objectSubType;
        }
        if ($this->description !== null) {
            $array['description'] = $this->description;
        }
        if ($this->showInRest) {
            $array['show_in_rest'] = ['schema' => $this->field->toArray()];
        }
        if ($this->authCallback !== null) {
            $array['auth_callback'] = $this->authCallback;
        }
        if ($this->sanitizeCallback !== null) {
            $array['sanitize_callback'] = $this->sanitizeCallback;
        }

        return $array;
    }
}
