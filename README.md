# kaiseki/wp-meta

Type-safe WordPress meta registration through a fluent field DSL.

You describe each meta field with a typed builder (`StringField`, `IntegerField`,
`ObjectField`, …). From that description the package builds the `register_meta()`
arguments and the REST `show_in_rest` JSON schema, so WordPress validates REST
requests against the field's type and constraints. The library stays intentionally
minimal — write-time sanitization is opt-in via `withSanitizeCallback()`.

## Installation

```bash
composer require kaiseki/wp-meta
```

Requires PHP 8.2 or newer.

## Usage

Implement `MetaDataBuilderInterface` and return your `MetaData` definitions from
`buildMetaData()`:

```php
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;
use Kaiseki\WordPress\Meta\MetaDataBuilderInterface;

final class ProductMeta implements MetaDataBuilderInterface
{
    /**
     * @return list<MetaData>
     */
    public function buildMetaData(): array
    {
        return [
            MetaData::post(
                'product',
                'sku',
                StringField::create()->withMaxLength(32),
            )->withShowInRest(),
            MetaData::post(
                'product',
                'stock',
                IntegerField::create(0)->withMinimum(0),
            )->withShowInRest(),
        ];
    }
}
```

### Scalar fields

```php
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\Field\StringFormat;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post(
    'page',
    'contact_email',
    StringField::create()
        ->withFormat(StringFormat::Email)
        ->withMaxLength(254),
)->withShowInRest();
```

### Nullable fields

Nullability is opt-in and independent of the default. A field is non-nullable
unless you call `->nullable()`, which makes `null` a valid value and emits a
`['<type>', 'null']` schema type:

```php
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post(
    'page',
    'subtitle',
    StringField::create()->nullable(),
)->withShowInRest();
```

### Numeric fields

```php
use Kaiseki\WordPress\Meta\Field\NumberField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post(
    'product',
    'rating',
    NumberField::create(0.0)
        ->withMinimum(0)
        ->withMaximum(5),
)->withShowInRest();
```

### Object fields

`ObjectField` composes other fields as properties. Mark properties as required
and decide whether extra keys are allowed with `withAdditionalProperties()`:

```php
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\Field\ObjectField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post(
    'page',
    'address',
    ObjectField::create()
        ->withProperty('street', StringField::create(), required: true)
        ->withProperty('zip', StringField::create())
        ->withProperty('floor', IntegerField::create())
        ->withAdditionalProperties(false),
)->withShowInRest();
```

### Array fields

```php
use Kaiseki\WordPress\Meta\Field\ArrayField;
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post(
    'post',
    'keywords',
    ArrayField::create(StringField::create())
        ->withMaxItems(10)
        ->withUniqueItems(),
)->withShowInRest();
```

### Non-post object types

`MetaData` also targets terms, users, and comments. Terms take a taxonomy; users
and comments take only a meta key:

```php
use Kaiseki\WordPress\Meta\Field\StringField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::term('category', 'color', StringField::create())
    ->withShowInRest();

MetaData::user('bio', StringField::create())
    ->withDescription('The user biography.')
    ->withShowInRest();
```

### Registering the builder

The package ships a `ConfigProvider` that registers `MetaDataRegistry` as a hook
provider (via `kaiseki/wp-hook`) and wires its factory. List your builders under
the `meta.data_builder` config key — entries are container service ids the
registry resolves:

```php
use Kaiseki\WordPress\Meta\ConfigProvider;

return [
    'meta' => [
        'data_builder' => [
            ProductMeta::class,
        ],
    ],
    ...(new ConfigProvider())(),
];
```

On the `init` hook the registry builds every definition and calls
`register_meta()` with the generated arguments.

### Validation model

By design the library does as little as possible at runtime. From each field it
builds the `register_meta()` arguments and, when you call `withShowInRest()`, a
JSON schema. WordPress validates incoming **REST** requests against that schema
before they are written.

Writes that bypass REST (for example a direct `update_post_meta()` call) are
**not** sanitized by default — the value is stored as given. If you want
write-time coercion, opt in per definition with `withSanitizeCallback()`, which
maps onto `register_meta()`'s native `sanitize_callback`:

```php
use Kaiseki\WordPress\Meta\Field\IntegerField;
use Kaiseki\WordPress\Meta\MetaData;

MetaData::post('product', 'stock', IntegerField::create(0))
    ->withShowInRest()
    ->withSanitizeCallback(static fn(mixed $value): int => (int) $value);
```

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan, phpunit
```

## License

MIT — see [LICENSE](LICENSE).
