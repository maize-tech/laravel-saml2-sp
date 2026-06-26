# Upgrading

## From 1.x to 2.0

Version 2.0 modernises the package with a facade, configurable user resolution,
just-in-time provisioning, multi-tenant routing, graceful error handling and a
couple of Artisan commands. Most defaults are unchanged, so a typical upgrade is
limited to publishing the new config keys and running one migration.

### Requirements

No change: PHP `^8.2` and Laravel `^11.0|^12.0|^13.0`.

### 1. Update the dependency

```bash
composer require maize-tech/laravel-saml2-sp:^2.0
```

### 2. Run the new migration

A nullable, unique `key` column was added to the `saml_configs` table to support
multi-tenant routing. Publish and run the migrations:

```bash
php artisan vendor:publish --tag="saml2-sp-migrations"
php artisan migrate
```

The `add_key_to_saml_configs_table` migration is idempotent: it is a no-op if the
column already exists.

### 3. Review the new config keys

Re-publish the config file (or merge these keys into your existing one):

```bash
php artisan vendor:publish --tag="saml2-sp-config"
```

New keys, all with backward-compatible defaults:

- `user_identifier.column` (default `email`) and `user_identifier.saml_attribute`
  (default `null` → use the nameId). These replace the previously hard-coded
  `email = nameId` lookup; the defaults reproduce the 1.x behaviour.
- `jit_provisioning.enabled` (default `false`) and `jit_provisioning.attribute_map`.
- `error_return_url` (default `null` → falls back to `logout_return_url`).
- `routes.key_parameter` (default `null` → routes are unchanged).

### 4. Breaking changes

#### Event signatures

`SamlLoggedIn` and `SamlLoggedOut` now use additional, promoted constructor
arguments and `readonly` properties:

- `SamlLoggedIn::__construct(Authenticatable $user, SamlUserData $userData, ?SamlConfig $config = null)`
- `SamlLoggedOut::__construct(Authenticatable $user, ?SamlConfig $config = null)`

If you dispatch these events manually (uncommon — the package does it for you),
update the call sites. **Listeners** that only read `$event->user` need no change.

#### Failed authentication no longer surfaces as a raw exception

Previously an invalid assertion bubbled up as a `OneLogin\Saml2\Error` (HTTP 500).
Now, while `app.debug` is `false`, `Maize\Saml2Sp\SamlError` renders itself as a
redirect to `error_return_url` and a `SamlLoginFailed` event is dispatched. If you
relied on catching the raw exception, listen to `SamlLoginFailed` instead, or set
`app.debug` to `true` in non-production environments.

#### User not found now throws `SamlError`

When no user matches and JIT provisioning is disabled, the default
`AuthenticateUser` action throws `Maize\Saml2Sp\SamlError`
(code `SamlError::SAML_USER_NOT_FOUND`) instead of an
`Illuminate\Database\Eloquent\ModelNotFoundException`. This integrates with the
graceful error handling above.

### 5. Optional new features

- **Facade**: `Maize\Saml2Sp\Facades\Saml2Sp` (auto-aliased as `Saml2Sp`) exposes
  `config()`, `auth()`, `metadata()`, `loginUrl()` and `logoutUrl()`.
- **Multi-tenant routing**: set `config_finder` to
  `Maize\Saml2Sp\RouteKeySamlConfigFinder` and `routes.key_parameter` to a route
  parameter name to serve multiple Identity Providers.
- **Artisan commands**: `saml2-sp:metadata` and `saml2-sp:certificate`.

See the [README](README.md) for details.
