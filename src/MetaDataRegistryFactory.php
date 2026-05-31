<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;

final class MetaDataRegistryFactory
{
    public function __invoke(ContainerInterface $container): MetaDataRegistry
    {
        /** @var list<class-string<MetaDataBuilderInterface>> $classNames */
        $classNames = Config::fromContainer($container)->array('meta.data_builder');
        /** @var list<MetaDataBuilderInterface> $builder */
        $builder = Config::initClassMap($container, $classNames);

        return new MetaDataRegistry($builder);
    }
}
