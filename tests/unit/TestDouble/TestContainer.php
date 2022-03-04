<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\TestDouble;

use Psr\Container\ContainerInterface;

use function array_key_exists;

final class TestContainer implements ContainerInterface
{
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->config[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->config);
    }
}
