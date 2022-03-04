<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta;

interface MetaDataBuilderInterface
{
    /**
     * @return list<MetaData>
     */
    public function buildMetaData(): array;
}
