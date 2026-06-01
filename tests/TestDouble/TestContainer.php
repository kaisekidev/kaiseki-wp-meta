<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta\TestDouble;

use Psr\Container\ContainerInterface;

use function array_key_exists;

final class TestContainer implements ContainerInterface
{
    /** @var array<string, mixed> */
    private array $entries;

    /**
     * @param array<string, mixed> $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function get(string $id): mixed
    {
        return $this->entries[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->entries);
    }
}
