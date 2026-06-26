<?php

use Maize\Saml2Sp\Facades\Saml2Sp;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlAuth;
use Maize\Saml2Sp\SamlError;

it('resolves the saml config through the finder', function () {
    $config = SamlConfig::factory()->create();

    expect(Saml2Sp::config()->is($config))->toBeTrue();
});

it('builds a saml auth instance from a config', function () {
    $config = SamlConfig::factory()->create();

    expect(Saml2Sp::auth($config))->toBeInstanceOf(SamlAuth::class);
});

it('throws when no config can be resolved for auth', function () {
    Saml2Sp::auth();
})->throws(SamlError::class);

it('generates the sp metadata', function () {
    $config = SamlConfig::factory()->create();

    expect(Saml2Sp::metadata($config))
        ->toContain('https://sp.test/saml2/metadata')
        ->toContain('EntityDescriptor');
});

it('builds the login url towards the idp', function () {
    $config = SamlConfig::factory()->create();

    $url = Saml2Sp::loginUrl($config, 'https://app.test/home');

    expect($url)->toStartWith('https://idp.test/saml2/sso')
        ->and($url)->toContain('SAMLRequest=');
});

it('builds the logout url towards the idp', function () {
    $config = SamlConfig::factory()->create();

    $url = Saml2Sp::logoutUrl($config, 'https://app.test/login');

    expect($url)->toStartWith('https://idp.test/saml2/slo')
        ->and($url)->toContain('SAMLRequest=');
});
