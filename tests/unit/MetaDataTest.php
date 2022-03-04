<?php

declare(strict_types=1);

namespace Kaiseki\Test\Unit\WordPress\Meta;

use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use Kaiseki\WordPress\Meta\MetaData;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;

final class MetaDataTest extends TestCase
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

    public function testIsSingleByDefault(): void
    {
        $data = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create());

        self::assertTrue($data->toArray()['single']);
    }

    public function testWithMultipleValuesByDefaultSetSingleToFalse(): void
    {
        $data = MetaData::post('post_type_name', 'my_meta_key', ObjectField::create())
            ->withMultipleValue();

        self::assertFalse($data->toArray()['single']);
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

    public function testExpected(): void
    {
        $nextDecember = (new DateTimeImmutable('1st december'))->format('Y-m-d H:i:s');
        $today = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $redirectUrl = 'https://www.kaiseki.dev';
        $authCallback = fn(): bool => current_user_can('edit_posts');
        $dateFormat = StringFormat::dateTime();

        $data = MetaData::post(
            'advent_calendar_post_type',
            'advent_calendar_meta_key',
            ObjectField::create()
                ->withAddedProperty('door_count', IntegerField::create(24)->withMinimum(1))
                ->withAddedProperty('door_ids', ArrayField::create(IntegerField::create(), []))
                ->withAddedProperty('door_permalink_prefix', StringField::create('day-'))
                ->withAddedProperty('redirect_url', StringField::create($redirectUrl)->withFormat(StringFormat::uri()))
                ->withAddedProperty(
                    'settings',
                    ObjectField::create()
                        ->withAddedProperty('hide_future_door_images', BooleanField::create(false))
                        ->withAddedProperty('hide_door_numbers', BooleanField::create(false))
                        ->withAddedProperty('leave_past_doors_open', BooleanField::create(false))
                        ->withAddedProperty('make_today_door_large', BooleanField::create(false))
                        ->withAddedProperty('make_today_door_first', BooleanField::create(false))
                        ->withAddedProperty('open_all_doors', BooleanField::create(false))
                        ->withAddedProperty('randomize_door_order', BooleanField::create(false))
                        ->withRequiredValue()
                )
                ->withAddedProperty('has_finished_setup', BooleanField::create(false))
                ->withAddedProperty('has_activated_simulation_date', BooleanField::create(false))
                ->withAddedProperty('calendar_simulation_date', StringField::create($today)->withFormat($dateFormat))
                ->withAddedProperty('calendar_start_date', StringField::create($nextDecember)->withFormat($dateFormat))
                ->withRequiredValue()
        )
            ->withShowInRest()
            ->withAuthCallback($authCallback);

        self::assertSame(
            [
                'object_subtype' => 'advent_calendar_post_type',
                'single' => true,
                'default' => [
                    'door_count' => 24,
                    'door_ids' => [],
                    'door_permalink_prefix' => 'day-',
                    'redirect_url' => $redirectUrl,
                    'settings' => [
                        'hide_future_door_images' => false,
                        'hide_door_numbers'       => false,
                        'leave_past_doors_open'   => false,
                        'make_today_door_large'   => false,
                        'make_today_door_first'   => false,
                        'open_all_doors'          => false,
                        'randomize_door_order'    => false,
                    ],
                    'has_finished_setup' => false,
                    'has_activated_simulation_date' => false,
                    'calendar_simulation_date' => $today,
                    'calendar_start_date' => $nextDecember,
                ],
                'type' => 'object',
                'show_in_rest' => [
                    'schema' => [
                        'type'  => 'object',
                        'properties' => [
                            'door_count' => [
                                'type' => 'integer',
                                'minimum' => 1,
                            ],
                            'door_ids' => [
                                'type'  => 'array',
                                'items' => [
                                    'type' => 'integer',
                                ],
                            ],
                            'door_permalink_prefix' => [
                                'type'  => 'string',
                            ],
                            'redirect_url' => [
                                'type'  => 'string',
                                'format' => 'uri',
                            ],
                            'settings' => [
                                'type' => 'object',
                                'properties' => [
                                    'hide_future_door_images' => ['type' => 'boolean'],
                                    'hide_door_numbers'       => ['type' => 'boolean'],
                                    'leave_past_doors_open'   => ['type' => 'boolean'],
                                    'make_today_door_large'   => ['type' => 'boolean'],
                                    'make_today_door_first'   => ['type' => 'boolean'],
                                    'open_all_doors'          => ['type' => 'boolean'],
                                    'randomize_door_order'    => ['type' => 'boolean'],
                                ],
                            ],
                            'has_finished_setup' => [
                                'type' => 'boolean',
                            ],
                            'has_activated_simulation_date' => ['type' => 'boolean'],
                            'calendar_simulation_date' => [
                                'type'  => 'string',
                                'format' => 'date-time',
                            ],
                            'calendar_start_date' => [
                                'type'  => 'string',
                                'format' => 'date-time',
                            ],
                        ],
                    ],
                ],
                'auth_callback' => $authCallback,
            ],
            $data->toArray()
        );
    }
}
