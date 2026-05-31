<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Kaiseki\Test\WordPress\Meta\TestDouble\DummyMetaDataBuilder;
use Kaiseki\Test\WordPress\Meta\TestDouble\TestContainer;
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
        $field = StringField::create()->withFormat(StringFormat::DateTime);
        $expected = MetaData::post('event', 'event_start_date', $field);
        $container = new TestContainer(
            [
                'config' => [
                    'meta' => [
                        'data_builder' => [
                            DummyMetaDataBuilder::class,
                        ],
                    ],
                ],
                DummyMetaDataBuilder::class => new DummyMetaDataBuilder([$expected]),
            ]
        );
        $instance = (new MetaDataRegistryFactory())($container);

        Functions\expect('register_meta')->once()->with(
            $expected->getObjectType(),
            $expected->getMetaKey(),
            $expected->toArray()
        );

        $instance->registerMeta();
    }

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
