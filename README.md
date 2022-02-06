# kaiseki/wp-meta

Description

## Install

```bash
composer require kaiseki/wp-meta
```

## Usage

````php
use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\BooleanField;
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use Kaiseki\WordPress\Meta\MetaData;

$metaData = MetaData::post(
    'advent_calendar_post_type',
    'advent_calendar_meta_key',
    ObjectField::create()
        ->withAddedProperty('door_count', IntegerField::create(24))
        ->withAddedProperty('door_ids', ArrayField::create(IntegerField::create()))
        ->withAddedProperty('door_permalink_prefix', StringField::create(null, 'day-'))
        ->withAddedProperty('redirect_url', StringField::create(StringFormat::uri()))
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
        )
        ->withAddedProperty('has_finished_setup', BooleanField::create(false))
        ->withAddedProperty('has_activated_simulation_date', BooleanField::create(false))
        ->withAddedProperty('calendar_simulation_date', StringField::create(StringFormat::dateTime()))
        ->withAddedProperty('calendar_start_date', StringField::create(StringFormat::dateTime()))
)
    ->withShowInRest()
    ->withAuthCallback(fn(): bool => current_user_can('edit_posts'));
````
