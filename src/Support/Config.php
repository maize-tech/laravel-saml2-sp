<?php

namespace Maize\Saml2Sp\Support;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlConfigFinder;
use Maize\Saml2Sp\SamlError;
use OneLogin\Saml2\Error;
use Spatie\Url\Url;

class Config
{
    /**
     * @throws Exception
     */
    public static function getUserModel(): Authenticatable
    {
        $model = config('saml2-sp.user_model')
            ?? throw new Exception('The user model is required.');

        return new $model;
    }

    public static function getAuthGuard(): ?string
    {
        return config('saml2-sp.auth_guard');
    }

    public static function getUserIdentifierColumn(): string
    {
        return config('saml2-sp.user_identifier.column')
            ?? 'email';
    }

    public static function getUserIdentifierSamlAttribute(): ?string
    {
        return config('saml2-sp.user_identifier.saml_attribute');
    }

    public static function getJitProvisioningEnabled(): bool
    {
        return config('saml2-sp.jit_provisioning.enabled')
            ?? false;
    }

    public static function getJitAttributeMap(): array
    {
        return config('saml2-sp.jit_provisioning.attribute_map')
            ?? [];
    }

    public static function getSamlConfigModel(): SamlConfig
    {
        $model = config('saml2-sp.config_model')
            ?? SamlConfig::class;

        return new $model;
    }

    public static function getSamlConfigFinder(): SamlConfigFinder
    {
        $finder = config('saml2-sp.config_finder')
            ?? DefaultSamlConfigFinder::class;

        return new $finder;
    }

    public static function getProxyVarsEnabled(): bool
    {
        return config('saml2-sp.proxy_vars_enabled')
            ?? false;
    }

    /**
     * @throws Error
     */
    public static function getLoginReturnURL(): string
    {
        $returnUrl = config('saml2-sp.login_return_url')
            ?? throw new SamlError(
                msg: 'The login return url is required.',
                code: SamlError::REDIRECT_INVALID_URL
            );

        if (is_string($returnUrl) && class_exists($returnUrl)) {
            $returnUrl = app($returnUrl);
        }

        return is_callable($returnUrl) ? $returnUrl() : $returnUrl;
    }

    /**
     * @throws Error
     */
    public static function getLogoutReturnURL(): string
    {
        $returnUrl = config('saml2-sp.logout_return_url')
            ?? throw new SamlError(
                msg: 'The logout return url is required.',
                code: SamlError::REDIRECT_INVALID_URL
            );

        if (is_string($returnUrl) && class_exists($returnUrl)) {
            $returnUrl = app($returnUrl);
        }

        return is_callable($returnUrl) ? $returnUrl() : $returnUrl;
    }

    public static function getErrorReturnURL(): string
    {
        $returnUrl = config('saml2-sp.error_return_url');

        if (is_null($returnUrl)) {
            try {
                return self::getLogoutReturnURL();
            } catch (Error) {
                return '/';
            }
        }

        if (is_string($returnUrl) && class_exists($returnUrl)) {
            $returnUrl = app($returnUrl);
        }

        return is_callable($returnUrl) ? $returnUrl() : $returnUrl;
    }

    public static function getDomainWhitelist(): array
    {
        $whitelist = config('saml2-sp.domain_whitelist')
            ?? [];

        return Arr::wrap($whitelist);
    }

    public static function isDomainWhitelisted(string $endpoint): bool
    {
        $host = str(
            Url::fromString($endpoint)->getHost()
        );

        return collect(
            self::getDomainWhitelist()
        )->some(
            fn ($domain) => $host->is($domain)
        );
    }

    public static function getRoutesEnabled(): bool
    {
        return config('saml2-sp.routes.enabled')
            ?? false;
    }

    public static function getRoutesPrefix(): string
    {
        return config('saml2-sp.routes.prefix')
            ?? 'saml2';
    }

    public static function getRoutesMiddleware(): array
    {
        $middleware = config('saml2-sp.routes.middleware')
            ?? [];

        return Arr::wrap($middleware);
    }

    public static function getRoutesKeyParameter(): ?string
    {
        return config('saml2-sp.routes.key_parameter');
    }

    /**
     * @throws Exception
     */
    public static function getAuthenticateUserAction(): string
    {
        return config('saml2-sp.actions.authenticate_user')
            ?? throw new Exception('The authenticate user action is required.');
    }

    /**
     * @throws Exception
     */
    public static function getLogoutUserAction(): string
    {
        return config('saml2-sp.actions.logout_user')
            ?? throw new Exception('The logout user action is required.');
    }

    public static function getDefaultSamlValue(string $attribute, mixed $value = null): mixed
    {
        $defaultValues = config("saml2-sp.default_values.{$attribute}");

        if (! is_array($value)) {
            return $value ?? $defaultValues;
        }

        return array_replace_recursive($defaultValues, $value);
    }
}
