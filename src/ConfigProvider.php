<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
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
        ];
    }
}
