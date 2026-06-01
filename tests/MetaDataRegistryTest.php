<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Kaiseki\Test\WordPress\Meta\TestDouble\DummyMetaDataBuilder;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;
use Kaiseki\WordPress\Meta\MetaDataRegistry;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

use function has_action;

final class MetaDataRegistryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testAddHooksAddsCallbackToInit(): void
    {
        $builder = new DummyMetaDataBuilder([MetaData::post('post_type', 'meta_key', StringField::create())]);
        $registry = new MetaDataRegistry([$builder]);

        $registry->addHooks();

        self::assertSame(10, has_action('init', [$registry, 'registerMeta']));
    }

    public function testRegisterMetaCallsRegisterMetaForEachMetaData(): void
    {
        $builder = new DummyMetaDataBuilder([
            MetaData::post('post_type', 'meta_key', StringField::create()),
            MetaData::user('user_key', StringField::create()),
        ]);
        $registry = new MetaDataRegistry([$builder]);

        /** @var list<string> $metaKeys */
        $metaKeys = [];
        Functions\when('register_meta')->alias(
            static function (string $objectType, string $metaKey, array $args) use (&$metaKeys): bool {
                $metaKeys[] = $metaKey;

                return true;
            }
        );

        $registry->registerMeta();

        self::assertSame(['meta_key', 'user_key'], $metaKeys);
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
