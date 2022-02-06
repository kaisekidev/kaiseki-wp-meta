<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\MetaData;
use PHPUnit\Framework\TestCase;

class MetaDataTest extends TestCase
{
    public function testCreatingMetaDataWithFieldSetsTypeToIt(): void
    {
        $expected = ObjectField::create();
        $data = MetaData::post('post_type_name', 'my_meta_key', $expected);

        self::assertSame($expected->getType(), $data->toArray()['type']);
    }

    public function testCreatingMetaDataWithMetaKey(): void
    {
        $expected = 'my_meta_key';
        $data = MetaData::post('post_type_name', $expected, ObjectField::create());

        self::assertSame($expected, $data->getMetaKey());
    }

    public function testCreatingMetaDataViaPostMethodSetsObjectTypeToPost(): void
    {
        $data = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create());

        self::assertSame('post', $data->getObjectType());
    }

    public function testCreatingMetaDataWithPostTypeSetsObjectSubTypeToIt(): void
    {
        $expected = 'post_type_name';
        $data = MetaData::post($expected, 'my_meta_key', ObjectField::create());

        self::assertSame($expected, $data->toArray()['object_subtype']);
    }

    public function testShowInRestGeneratesSchema(): void
    {
        $data = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create())
            ->withShowInRest();

        $showInRest = $data->toArray()['show_in_rest'] ?? null;

        self::assertIsArray($showInRest);
        self::assertArrayHasKey('schema', $showInRest);
    }

    public function testAuthCallbackIs(): void
    {
        $callback = fn (): bool => true;
        $data = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create())
            ->withAuthCallback($callback);

        $authCallback = $data->toArray()['auth_callback'] ?? null;

        self::assertIsCallable($authCallback);
        self::assertSame($callback, $authCallback);
    }

    /**
     * @dataProvider metaDataClonesCases
     * @param callable(MetaData): MetaData $modify
     */
    public function testMetaDataClones(callable $modify): void
    {
        $original = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create());

        $modified = ($modify)($original);

        self::assertNotSame($original, $modified);
    }

    /**
     * @return iterable<string, array{callable(MetaData): MetaData}>
     */
    public function metaDataClonesCases(): iterable
    {
        $cb = fn(): bool => true;
        yield 'showInRest' => [fn(MetaData $data): MetaData => $data->withShowInRest()];
        yield 'singleValue' => [fn(MetaData $data): MetaData => $data->withMultipleValue()];
        yield 'authCallback' => [fn(MetaData $data): MetaData => $data->withAuthCallback($cb)];
    }
}
