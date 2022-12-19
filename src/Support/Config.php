<?php

namespace Maize\Saml2Sp\Support;

use Illuminate\Support\Arr;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlConfigFinder;
use OneLogin\Saml2\Error;

class Config
{
    public static function getSamlConfigModel(): SamlConfig
    {
        $model = config('saml-sp.config_model')
            ?? SamlConfig::class;

        return new $model;
    }

    public static function getSamlConfigFinder(): SamlConfigFinder
    {
        $finder = config('saml-sp.config_finder')
            ?? DefaultSamlConfigFinder::class;

        return new $finder;
    }

    /**
     * @throws Error
     */
    public static function getLoginReturnURL(): string
    {
        return config('saml-sp.login_return_url')
            ?? throw new Error('The login return url is required.', Error::REDIRECT_INVALID_URL);
    }

    /**
     * @throws Error
     */
    public static function getLogoutReturnURL(): string
    {
        return config('saml-sp.logout_return_url')
            ?? throw new Error('The logout return url is required.', Error::REDIRECT_INVALID_URL);
    }

    public static function getDomainWhitelist(): array
    {
        return config('saml-sp.domain_whitelist')
            ?? [];
    }

    public static function isDomainWhitelisted(string $endpoint): bool
    {
        $endpoint = UrlUtils::getUrlDomain($endpoint);

        return collect(self::getDomainWhitelist())
            ->map(fn ($url) => UrlUtils::getUrlDomain($url))
            ->contains($endpoint);
    }

    public static function getRoutesEnabled(): bool
    {
        return config('magic-login.routes.enabled')
            ?? false;
    }

    public static function getRoutesPrefix(): string
    {
        return config('magic-login.routes.prefix')
            ?? 'saml2';
    }

    public static function getRoutesMiddleware(): array
    {
        $middleware = config('magic-login.routes.middleware')
            ?? [];

        return Arr::wrap($middleware);
    }

    public static function getDefaultSamlValues(string $attribute): array
    {
        return config("saml-sp.default_values.{$attribute}")
            ?? [];
    }
}
