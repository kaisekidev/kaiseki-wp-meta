<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

use Kaiseki\WordPress\Config\Config;
use Psr\Container\ContainerInterface;

final class MetaDataRegistryFactory
{
    public function __invoke(ContainerInterface $container): MetaDataRegistry
    {
        /** @var list<class-string<MetaDataBuilderInterface>> $classNames */
        $classNames = Config::get($container)->array('meta/data_builder');
        $builder = Config::initClassMap($container, $classNames);
        return new MetaDataRegistry($builder);
    }
}
