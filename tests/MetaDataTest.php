<?php

declare(strict_types=1);

namespace Kaiseki\Test\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;
use PHPUnit\Framework\TestCase;

final class MetaDataTest extends TestCase
{
    public function testPostCreatesMetaData(): void
    {
        $metaData = MetaData::post('page', 'my_key', StringField::create());
        $result = $metaData->toArray();

        self::assertSame('post', $metaData->getObjectType());
        self::assertSame('my_key', $metaData->getMetaKey());
        self::assertTrue($result['single']);
        self::assertArrayHasKey('object_subtype', $result);
        self::assertSame('page', $result['object_subtype']);
        self::assertSame('string', $result['type']);
        self::assertNull($result['default']);
        self::assertArrayNotHasKey('sanitize_callback', $result);
    }

    public function testTermIncludesObjectSubtype(): void
    {
        $metaData = MetaData::term('category', 'color', StringField::create());
        $result = $metaData->toArray();

        self::assertSame('term', $metaData->getObjectType());
        self::assertArrayHasKey('object_subtype', $result);
        self::assertSame('category', $result['object_subtype']);
    }

    public function testUserHasNoObjectSubtype(): void
    {
        $metaData = MetaData::user('nickname', StringField::create());
        $result = $metaData->toArray();

        self::assertSame('user', $metaData->getObjectType());
        self::assertArrayNotHasKey('object_subtype', $result);
    }

    public function testCommentHasNoObjectSubtype(): void
    {
        $metaData = MetaData::comment('rating', IntegerField::create());
        $result = $metaData->toArray();

        self::assertSame('comment', $metaData->getObjectType());
        self::assertArrayNotHasKey('object_subtype', $result);
    }

    public function testWithMultipleValue(): void
    {
        $result = MetaData::user('tags', StringField::create())
            ->withMultipleValue()
            ->toArray();

        self::assertFalse($result['single']);
    }

    public function testWithDescription(): void
    {
        $result = MetaData::user('bio', StringField::create())
            ->withDescription('The user biography.')
            ->toArray();

        self::assertArrayHasKey('description', $result);
        self::assertSame('The user biography.', $result['description']);
    }

    public function testDescriptionAbsentByDefault(): void
    {
        $result = MetaData::user('bio', StringField::create())->toArray();

        self::assertArrayNotHasKey('description', $result);
    }

    public function testWithShowInRestEmitsSchema(): void
    {
        $result = MetaData::user('bio', StringField::create('x'))
            ->withShowInRest()
            ->toArray();

        self::assertArrayHasKey('show_in_rest', $result);
        $showInRest = $result['show_in_rest'];
        self::assertIsArray($showInRest);
        self::assertSame(['type' => 'string'], $showInRest['schema']);
    }

    public function testShowInRestAbsentByDefault(): void
    {
        $result = MetaData::user('bio', StringField::create())->toArray();

        self::assertArrayNotHasKey('show_in_rest', $result);
    }

    public function testWithAuthCallback(): void
    {
        $callback = static fn(): bool => true;
        $result = MetaData::user('bio', StringField::create())
            ->withAuthCallback($callback)
            ->toArray();

        self::assertArrayHasKey('auth_callback', $result);
        self::assertSame($callback, $result['auth_callback']);
    }

    public function testAuthCallbackAbsentByDefault(): void
    {
        $result = MetaData::user('bio', StringField::create())->toArray();

        self::assertArrayNotHasKey('auth_callback', $result);
    }

    public function testSanitizeCallbackAbsentByDefault(): void
    {
        $result = MetaData::user('count', IntegerField::create())->toArray();

        self::assertArrayNotHasKey('sanitize_callback', $result);
    }

    public function testWithSanitizeCallbackOptsIn(): void
    {
        $callback = static fn(mixed $value): mixed => $value;
        $result = MetaData::user('count', IntegerField::create())
            ->withSanitizeCallback($callback)
            ->toArray();

        self::assertArrayHasKey('sanitize_callback', $result);
        self::assertSame($callback, $result['sanitize_callback']);
    }
}
