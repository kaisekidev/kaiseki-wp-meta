<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta\TestDouble;

use Kaiseki\WordPress\Meta\MetaData;
use Kaiseki\WordPress\Meta\MetaDataBuilderInterface;

final class DummyMetaDataBuilder implements MetaDataBuilderInterface
{
    /** @var list<MetaData> */
    private array $metaData;

    /**
     * @param list<MetaData> $metaData
     */
    public function __construct(array $metaData)
    {
        $this->metaData = $metaData;
    }

    /**
     * @return list<MetaData>
     */
    public function buildMetaData(): array
    {
        return $this->metaData;
    }
}
