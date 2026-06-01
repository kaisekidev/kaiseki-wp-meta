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

    public function testFactoryResolvesBuildersFromConfigAndRegistersMeta(): void
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

        /** @var list<array{string, string, array<string, mixed>}> $calls */
        $calls = [];
        Functions\when('register_meta')->alias(
            static function (string $objectType, string $metaKey, array $args) use (&$calls): bool {
                $calls[] = [$objectType, $metaKey, $args];

                return true;
            }
        );

        $instance->registerMeta();

        self::assertCount(1, $calls);
        self::assertSame('post', $calls[0][0]);
        self::assertSame('event_start_date', $calls[0][1]);
        self::assertSame('event', $calls[0][2]['object_subtype']);
        self::assertSame('string', $calls[0][2]['type']);
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
