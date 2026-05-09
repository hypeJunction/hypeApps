# hypeApps plugin architecture (Elgg 6.x)

Foundational bootstrap library shared by every hypeJunction plugin. Provides a
DI-container–based Plugin singleton pattern, controller/action infrastructure, file
upload helpers, icon handling, and a `graph:properties` event system for serialising
any Elgg entity to a property map.

## Layout

```
hypeapps/
├── composer.json             plugin metadata; requires smottt/wideimage ~1.1.1 + elgg ^5.0
├── elgg-plugin.php           declarative config — bootstrap + events; no routes or actions declared here
├── autoloader.php            loads vendor/ autoload + lib/shims/
├── lib/
│   ├── autoloader.php        classmap + psr-0 shim
│   └── shims/1_8.php         Elgg 1.8 compat shims (legacy)
├── servers/icon.php          icon-serving endpoint (legacy server-style)
├── actions/dispatch.php      catch-all action dispatcher — delegates to hypeApps()->actions->execute()
├── classes/hypeJunction/
│   ├── Plugin.php            abstract base (extends DiContainer) for all hypeJunction plugin singletons
│   ├── Di/DiContainer.php    lightweight DI container — setValue/setFactory/__get
│   ├── Config.php            base config container backed by ElggPlugin settings
│   ├── Integration.php       optional integration helpers
│   ├── BatchResult.php       batch-operation result carrier
│   ├── Controllers/
│   │   ├── AbstractController.php / ControllerInterface.php   action controller contract
│   │   ├── Action.php / Actions.php                           action registry + dispatcher
│   │   ├── ActionResult.php                                   output + forward-URL carrier
│   │   ├── DeleteAction.php                                   generic delete action
│   │   ├── ParameterBag.php / ParameterBagInterface.php       typed request param accessor
│   │   └── ControllerException.php
│   ├── Data/
│   │   ├── Graph.php / GraphInterface.php                     assembles entity property maps via event
│   │   ├── Property.php / PropertyInterface.php               single named property carrier
│   │   ├── Values.php                                         multi-value property set
│   │   └── Validators.php                                     shared validation helpers
│   ├── Exceptions/           ActionValidationException / InvalidEntityException / PermissionsException / UnknownClassException
│   ├── Files/
│   │   ├── Image.php         WideImage wrapper for resize/crop
│   │   └── Upload.php        normalises PHP upload superglobal
│   ├── Filestore/
│   │   ├── UploadHandler.php saves uploaded file to Elgg filestore
│   │   ├── IconHandler.php   resizes + stores entity icons
│   │   └── CoverHandler.php  resizes + stores cover images
│   ├── Http/
│   │   ├── Request.php / RequestInterface.php    wraps Symfony request
│   │   └── Response.php / ResponseInterface.php  wraps Elgg response
│   ├── Servers/IconServer.php  serves icon files from filestore
│   └── Apps/
│       ├── Bootstrap.php     DefaultPluginBootstrap — boot() requires autoloader.php
│       ├── Plugin.php        hypeApps singleton; wires Config/Actions/Graph/Uploader/IconFactory
│       ├── Config.php        icon sizes (topbar/tiny/small/medium/large/master), filestore prefixes
│       └── Handlers/
│           ├── PropertiesHook.php          graph:properties / all   — base property set
│           ├── UserPropertiesHook.php      graph:properties / user
│           ├── GroupPropertiesHook.php     graph:properties / group
│           ├── SitePropertiesHook.php      graph:properties / site
│           ├── ObjectPropertiesHook.php    graph:properties / object
│           ├── BlogPropertiesHook.php      graph:properties / object:blog
│           ├── FilePropertiesHook.php      graph:properties / object:file
│           ├── MessagePropertiesHook.php   graph:properties / object:messages
│           ├── ExtenderPropertiesHook.php  graph:properties / metadata|annotation|relationship|river:item
│           ├── RiverPropertiesHook.php     graph:properties / river:item
│           └── EntityIconUrlHook.php       entity:icon:url / all
```

## Registered events (elgg-plugin.php)

| Kind  | Identifier | Handler |
|-------|-----------|---------|
| event | `entity:icon:url` / `all`          | `EntityIconUrlHook::handle` |
| event | `graph:properties` / `all`         | `PropertiesHook::handle` |
| event | `graph:properties` / `user`        | `UserPropertiesHook::handle` |
| event | `graph:properties` / `group`       | `GroupPropertiesHook::handle` |
| event | `graph:properties` / `site`        | `SitePropertiesHook::handle` |
| event | `graph:properties` / `object`      | `ObjectPropertiesHook::handle` |
| event | `graph:properties` / `object:blog` | `BlogPropertiesHook::handle` |
| event | `graph:properties` / `object:file` | `FilePropertiesHook::handle` |
| event | `graph:properties` / `object:messages` | `MessagePropertiesHook::handle` |
| event | `graph:properties` / `metadata`    | `ExtenderPropertiesHook::handle` |
| event | `graph:properties` / `annotation`  | `ExtenderPropertiesHook::handle` |
| event | `graph:properties` / `relationship`| `ExtenderPropertiesHook::handle` |
| event | `graph:properties` / `river:item`  | `RiverPropertiesHook::handle` |

All handlers have the signature `public static function handle(\Elgg\Event $event)`.

## Routes

None declared in `elgg-plugin.php`. Icon serving uses the legacy `servers/icon.php`
endpoint, and action dispatch uses `actions/dispatch.php` as a catch-all.

## Actions

`actions/dispatch.php` — catch-all; expects `$action` to be set in context and calls
`hypeApps()->actions->execute($action)`.

## Entities

No custom entity classes. Operates on any `ElggEntity` / extender via events.

## Dependencies

| Package | Constraint | Role |
|---------|-----------|------|
| `elgg/elgg` | `^5.0` | framework |
| `smottt/wideimage` | `~1.1.1` | image resize/crop |
| `composer/installers` | `^2.0` | Elgg plugin install path |

## Migration notes (4.x → 5.x)

- `'hooks'` key in `elgg-plugin.php` renamed to `'events'` per Elgg 5.x unified events API.
- All handler signatures changed from `\Elgg\Hook` to `\Elgg\Event`; both classes have
  the same `getValue()`, `getParam()`, `getType()`, `getName()` interface.
- `add_translation()` removed; language files must now `return []`.
- `version.php` removed from Elgg core; version is now via `elgg_get_config('version')`.
- `DiContainer` and `ParameterBag` annotated with `#[\AllowDynamicProperties]`
  to silence PHP 8.2 deprecation (both classes intentionally store values as properties).
- `graph:properties` is a hypeApps-internal event (not part of Elgg core). Other
  plugins that consume it must list `hypeapps` as a `required` dependency.
- Icon sizes defined in `Config::SIZE_*` constants are referenced by other
  hypeJunction plugins; do not rename without auditing callsites.
