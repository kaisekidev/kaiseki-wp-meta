<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

use function add_action;
use function register_meta;

final class MetaDataRegistry implements HookCallbackProviderInterface
{
    /** @var list<MetaDataBuilderInterface> */
    private array $builder;

    /**
     * @param list<MetaDataBuilderInterface> $builder
     */
    public function __construct(array $builder)
    {
        $this->builder = $builder;
    }

    public function registerCallbacks(): void
    {
        add_action('init', [$this, 'registerMeta']);
    }

    public function registerMeta(): void
    {
        foreach ($this->builder as $builder) {
            foreach ($builder->buildMetaData() as $metaData) {
                register_meta($metaData->getObjectType(), $metaData->getMetaKey(), $metaData->toArray());
            }
        }
    }
}
