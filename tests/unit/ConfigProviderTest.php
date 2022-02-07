<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\ConfigProvider;
use Kaiseki\WordPress\Meta\MetaDataRegistry;
use Kaiseki\WordPress\Meta\MetaDataRegistryFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    public function testConfig(): void
    {
        self::assertSame(
            [
                'meta' => [
                    'data_builder' => [],
                ],
                'hook' => [
                    'provider' => [
                        MetaDataRegistry::class,
                    ],
                ],
                'dependencies' => [
                    'aliases' => [],
                    'factories' => [
                        MetaDataRegistry::class => MetaDataRegistryFactory::class,
                    ],
                ],
            ],
            (new ConfigProvider())()
        );
    }
}
