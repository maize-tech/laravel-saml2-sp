<?php

use Illuminate\Foundation\Auth\User;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlError;
use Maize\Saml2Sp\Support\Config;

it('returns the configured user model', function () {
    expect(Config::getUserModel())->toBeInstanceOf(User::class);
});

it('throws when the user model is not configured', function () {
    config()->set('saml2-sp.user_model', null);

    Config::getUserModel();
})->throws(Exception::class, 'The user model is required.');

it('returns the configured auth guard', function () {
    expect(Config::getAuthGuard())->toBeNull();

    config()->set('saml2-sp.auth_guard', 'web');

    expect(Config::getAuthGuard())->toBe('web');
});

it('returns the saml config model instance', function () {
    expect(Config::getSamlConfigModel())->toBeInstanceOf(SamlConfig::class);
});

it('returns the saml config finder instance', function () {
    expect(Config::getSamlConfigFinder())->toBeInstanceOf(DefaultSamlConfigFinder::class);
});

it('returns the routes configuration with defaults', function () {
    config()->set('saml2-sp.routes', null);

    expect(Config::getRoutesEnabled())->toBeFalse()
        ->and(Config::getRoutesPrefix())->toBe('saml2')
        ->and(Config::getRoutesMiddleware())->toBe([]);
});

it('wraps the routes middleware into an array', function () {
    config()->set('saml2-sp.routes.middleware', 'web');

    expect(Config::getRoutesMiddleware())->toBe(['web']);
});

it('returns the login and logout return urls as strings', function () {
    config()->set('saml2-sp.login_return_url', 'https://app.test/home');
    config()->set('saml2-sp.logout_return_url', 'https://app.test/login');

    expect(Config::getLoginReturnURL())->toBe('https://app.test/home')
        ->and(Config::getLogoutReturnURL())->toBe('https://app.test/login');
});

it('resolves the return url from a callable', function () {
    config()->set('saml2-sp.login_return_url', fn () => 'https://app.test/from-callable');

    expect(Config::getLoginReturnURL())->toBe('https://app.test/from-callable');
});

it('resolves the return url from an invokable class', function () {
    config()->set('saml2-sp.login_return_url', InvokableReturnUrl::class);

    expect(Config::getLoginReturnURL())->toBe('https://app.test/from-class');
});

it('throws when the login return url is missing', function () {
    config()->set('saml2-sp.login_return_url', null);

    Config::getLoginReturnURL();
})->throws(SamlError::class, 'The login return url is required.');

it('throws when the logout return url is missing', function () {
    config()->set('saml2-sp.logout_return_url', null);

    Config::getLogoutReturnURL();
})->throws(SamlError::class, 'The logout return url is required.');

it('returns the user identifier defaults', function () {
    expect(Config::getUserIdentifierColumn())->toBe('email')
        ->and(Config::getUserIdentifierSamlAttribute())->toBeNull();

    config()->set('saml2-sp.user_identifier.column', 'username');
    config()->set('saml2-sp.user_identifier.saml_attribute', 'uid');

    expect(Config::getUserIdentifierColumn())->toBe('username')
        ->and(Config::getUserIdentifierSamlAttribute())->toBe('uid');
});

it('returns the jit provisioning configuration', function () {
    expect(Config::getJitProvisioningEnabled())->toBeFalse()
        ->and(Config::getJitAttributeMap())->toBe([]);

    config()->set('saml2-sp.jit_provisioning.enabled', true);
    config()->set('saml2-sp.jit_provisioning.attribute_map', ['name' => 'displayName']);

    expect(Config::getJitProvisioningEnabled())->toBeTrue()
        ->and(Config::getJitAttributeMap())->toBe(['name' => 'displayName']);
});

it('returns the route key parameter', function () {
    expect(Config::getRoutesKeyParameter())->toBeNull();

    config()->set('saml2-sp.routes.key_parameter', 'saml_config');

    expect(Config::getRoutesKeyParameter())->toBe('saml_config');
});

it('falls back to the logout return url for the error return url', function () {
    config()->set('saml2-sp.error_return_url', null);
    config()->set('saml2-sp.logout_return_url', 'https://app.test/login');

    expect(Config::getErrorReturnURL())->toBe('https://app.test/login');
});

it('returns the configured error return url', function () {
    config()->set('saml2-sp.error_return_url', 'https://app.test/oops');

    expect(Config::getErrorReturnURL())->toBe('https://app.test/oops');
});

it('detects whitelisted domains', function () {
    config()->set('saml2-sp.domain_whitelist', ['app.test']);

    expect(Config::isDomainWhitelisted('https://app.test/home'))->toBeTrue()
        ->and(Config::isDomainWhitelisted('https://evil.example/home'))->toBeFalse();
});

it('wraps a single whitelisted domain into an array', function () {
    config()->set('saml2-sp.domain_whitelist', 'app.test');

    expect(Config::getDomainWhitelist())->toBe(['app.test']);
});

it('merges array values recursively with the default values', function () {
    config()->set('saml2-sp.default_values.sp', [
        'entityId' => 'default-entity',
        'assertionConsumerService' => ['url' => 'default-acs'],
    ]);

    $merged = Config::getDefaultSamlValue('sp', [
        'assertionConsumerService' => ['url' => 'custom-acs'],
    ]);

    expect($merged)->toBe([
        'entityId' => 'default-entity',
        'assertionConsumerService' => ['url' => 'custom-acs'],
    ]);
});

it('falls back to the default value for scalar attributes', function () {
    config()->set('saml2-sp.default_values.strict', true);

    expect(Config::getDefaultSamlValue('strict', null))->toBeTrue()
        ->and(Config::getDefaultSamlValue('strict', false))->toBeFalse();
});

class InvokableReturnUrl
{
    public function __invoke(): string
    {
        return 'https://app.test/from-class';
    }
}
