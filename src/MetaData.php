<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\FieldInterface;

final class MetaData
{
    private const OBJECT_TYPE_POST = 'post';
    private string $objectType;
    private string $objectSubType;
    private string $metaKey;
    private FieldInterface $field;
    private bool $showInRest = false;
    private bool $isSingle = true;
    /** @var callable(): bool|null */
    private $authCallback = null;

    private function __construct(string $objectType, string $postType, string $metaKey, FieldInterface $field)
    {
        $this->objectType = $objectType;
        $this->metaKey = $metaKey;
        $this->objectSubType = $postType;
        $this->field = $field;
    }

    public static function post(string $postType, string $metaKey, FieldInterface $field): self
    {
        return new self(self::OBJECT_TYPE_POST, $postType, $metaKey, $field);
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
     * @return array{
     *      object_subtype: string,
     *      show_in_rest?: array<string, mixed>,
     *      default: mixed,
     *      single: bool,
     *      type: string,
     *      auth_callback?: callable(): bool
     * }
     */
    public function toArray(): array
    {
        $array = [
            'object_subtype' => $this->objectSubType,
            'single' => $this->isSingle,
            'default' => $this->field->getDefault(),
            'type' => $this->field->getType(),
        ];
        if ($this->showInRest) {
            $array['show_in_rest'] = ['schema' => $this->field->toArray()];
        }
        if ($this->authCallback !== null) {
            $array['auth_callback'] = $this->authCallback;
        }
        return $array;
    }
}
