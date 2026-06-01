# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-06-01

### Added

- Typed field DSL for declaring WordPress meta: `StringField`, `IntegerField`,
  `NumberField`, `BooleanField`, `ArrayField`, and `ObjectField` (sharing
  `AbstractField`/`AbstractNumericField` and implementing `FieldInterface`). Each
  field emits a JSON schema via `toArray()`.
- Opt-in nullability decoupled from defaults: `->nullable()` makes `null` a valid
  value and emits a `['<type>', 'null']` schema type. Fields are non-nullable by
  default regardless of whether a default is set.
- REST schema validation through `withShowInRest()`, emitting the field's schema
  under `show_in_rest` so WordPress validates REST writes against it.
- Optional write-time sanitization: `MetaData::withSanitizeCallback()` registers a
  `sanitize_callback`. No callback is registered by default — the library stays
  minimal and leaves coercion to the consumer.
- `StringFormat` backed enum (`date-time`, `uri`, `email`, `ip`, `uuid`,
  `hex-color`) plus string constraints `withFormat()`, `withPattern()`,
  `withMinLength()`, and `withMaxLength()`.
- Numeric constraints `withMinimum()`, `withMaximum()`, `withExclusiveMinimum()`,
  `withExclusiveMaximum()`, and `withMultipleOf()`.
- `ArrayField` item constraints `withMinItems()`, `withMaxItems()`, and
  `withUniqueItems()`.
- `ObjectField` with `withProperty()` (including a `required` flag),
  `withAdditionalProperties()`, and assembled per-property defaults.
- `MetaData` constructors for `post()`, `term()`, `user()`, and `comment()`
  object types, plus `withMultipleValue()`, `withDescription()`,
  `withAuthCallback()`, and `withSanitizeCallback()` modifiers.
- `MetaDataBuilderInterface` for supplying `MetaData`, and `MetaDataRegistry`
  (a `kaiseki/wp-hook` hook provider) that hooks `init` and calls `register_meta()`
  for each built definition.
- `MetaDataRegistryFactory` reading the `meta.data_builder` config key via
  `kaiseki/config`, and `ConfigProvider` wiring the registry as both a hook
  provider and a container factory.
