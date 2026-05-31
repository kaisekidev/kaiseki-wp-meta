# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## Unreleased

### Added

- Typed field DSL — `StringField`, `IntegerField`, `NumberField`, `BooleanField`,
  `ArrayField`, and `ObjectField` (with shared `AbstractField`/`AbstractNumericField`
  bases and `FieldInterface`) for describing post meta, including defaults,
  nullability, numeric bounds, string length/pattern/format, and array/object
  constraints. `StringFormat` is a backed enum of the supported REST formats
  (`date-time`, `uri`, `email`, `ip`, `uuid`, `hex-color`).
- `MetaData` value object with a fluent `post()` builder and `withShowInRest()`,
  `withMultipleValue()`, and `withAuthCallback()` modifiers, producing the
  `register_meta()` argument array (and `show_in_rest` JSON schema).
- `MetaDataBuilderInterface` for supplying `MetaData`, and `MetaDataRegistry`
  (a `kaiseki/wp-hook` hook provider) that hooks `init` and calls
  `register_meta()` for each built `MetaData`.
- `MetaDataRegistryFactory` reading the `meta.data_builder` config key via
  `kaiseki/config`, and `ConfigProvider` wiring the registry as both a hook
  provider and a container factory.
