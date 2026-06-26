# Changelog

All notable changes to `laravel-saml2-sp` will be documented in this file.

## 2.0.0 - Unreleased

### Added

* `Saml2Sp` facade for programmatic access (`config`, `auth`, `metadata`, `loginUrl`, `logoutUrl`).
* Configurable user resolution via `user_identifier.column` / `user_identifier.saml_attribute`.
* Just-in-time user provisioning via `jit_provisioning.enabled` + `attribute_map`.
* Graceful error handling: `SamlError` renders to `error_return_url` (when `app.debug` is off) and dispatches the new `SamlLoginFailed` event.
* Multi-tenant routing: `RouteKeySamlConfigFinder`, a `key` column on `saml_configs` and the `routes.key_parameter` option.
* Artisan commands `saml2-sp:metadata` and `saml2-sp:certificate`.

### Changed

* **Breaking:** `SamlLoggedIn` now carries `$userData` and `$config`; `SamlLoggedOut` now carries an optional `$config`. Both events use `readonly` properties.
* **Breaking:** when no user matches and JIT is disabled, `AuthenticateUser` throws `SamlError` instead of `ModelNotFoundException`.
* Updated CI workflows, GitHub Actions versions and Dependabot (now also tracks Composer).

See [UPGRADING](UPGRADING.md) for migration details.

## 1.0.0 - 2026-06-26

### What's Changed

* Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/2
* Bump aglipanci/laravel-pint-action from 1.0.0 to 2.1.0 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/1
* Bump aglipanci/laravel-pint-action from 2.1.0 to 2.2.0 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/3
* Bump dependabot/fetch-metadata from 1.3.6 to 1.4.0 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/4
* Bump dependabot/fetch-metadata from 1.4.0 to 1.5.1 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/5
* Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/7
* Bump aglipanci/laravel-pint-action from 2.2.0 to 2.3.0 by @dependabot[bot] in https://github.com/maize-tech/laravel-saml2-sp/pull/6
* Add test suite and usage documentation by @enricodelazzari in https://github.com/maize-tech/laravel-saml2-sp/pull/10

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/maize-tech/laravel-saml2-sp/pull/2
* @enricodelazzari made their first contribution in https://github.com/maize-tech/laravel-saml2-sp/pull/10

**Full Changelog**: https://github.com/maize-tech/laravel-saml2-sp/commits/1.0.0
