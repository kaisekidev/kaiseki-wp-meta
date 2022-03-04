<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\Test\Unit\WordPress\Meta\TestDouble\DummyMetaDataBuilder;
use Kaiseki\Test\Unit\WordPress\Meta\TestDouble\TestContainer;
use Kaiseki\WordPress\Config\ConfigInterface;
use Kaiseki\WordPress\Config\NestedArrayConfig;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use Kaiseki\WordPress\Meta\MetaData;
use Kaiseki\WordPress\Meta\MetaDataRegistryFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class MetaDataRegistryFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testFactory(): void
    {
        $field = StringField::create()->withFormat(StringFormat::dateTime());
        $expected = MetaData::post('event', 'event_start_date', $field);
        $container = new TestContainer(
            [
                ConfigInterface::class => new NestedArrayConfig(
                    [
                        'meta' => [
                            'data_builder' => [
                                DummyMetaDataBuilder::class,
                            ],
                        ],
                    ]
                ),
                DummyMetaDataBuilder::class => new DummyMetaDataBuilder([$expected]),
            ]
        );
        $instance = (new MetaDataRegistryFactory())($container);

        \Brain\Monkey\Functions\expect('register_meta')->with(
            $expected->getObjectType(),
            $expected->getMetaKey(),
            $expected->toArray()
        );

        $instance->registerMeta();
    }

    protected function setUp(): void
    {
        parent::setUp();
        \Brain\Monkey\setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        \Brain\Monkey\tearDown();
    }
}
