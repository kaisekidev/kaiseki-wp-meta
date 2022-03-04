<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;
use Kaiseki\WordPress\Meta\MetaDataBuilderInterface;
use Kaiseki\WordPress\Meta\MetaDataRegistry;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class MetaDataRegistryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testRegisterCallbacksAddsCallbackToInit(): void
    {
        $provider = $this->buildProvider(MetaData::post('post_type', 'meta_key', StringField::create()));
        $registry = new MetaDataRegistry([$provider]);

        $registry->registerCallbacks();

        self::assertSame(10, has_action('init', [$registry, 'registerMeta']));
    }

    public function testRegisterMetaCallsRegisterMetaFunction(): void
    {
        $provider = $this->buildProvider(MetaData::post('post_type', 'meta_key', StringField::create()));
        $registry = new MetaDataRegistry([$provider]);

        \Brain\Monkey\Functions\expect('register_meta')->once();

        $registry->registerMeta();
    }

    private function buildProvider(MetaData ...$data): MetaDataBuilderInterface
    {
        return new class (...$data) implements MetaDataBuilderInterface {
            /** @var list<MetaData> */
            private array $data;

            public function __construct(MetaData ...$data)
            {
                $this->data = $data;
            }

            /**
             * @return list<MetaData>
             */
            public function buildMetaData(): array
            {
                return $this->data;
            }
        };
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
