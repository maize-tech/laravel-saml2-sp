<?php

use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlUserData;
use OneLogin\Saml2\Settings;

function makeSamlUserData(array $attributes = [], array $friendly = []): SamlUserData
{
    $settings = new Settings(SamlConfig::factory()->make()->settings);

    return new SamlUserData(
        settings: $settings,
        attributes: $attributes,
        attributesWithFriendlyName: $friendly,
        nameId: 'john@app.test',
        nameIdFormat: 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        nameIdNameQualifier: null,
        nameIdSPNameQualifier: null,
    );
}

it('exposes the name id and identity data', function () {
    $userData = makeSamlUserData();

    expect($userData->nameId)->toBe('john@app.test')
        ->and($userData->nameIdFormat)->toBe('urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress');
});

it('returns a full attribute value', function () {
    $userData = makeSamlUserData([
        'mail' => ['john@app.test', 'john.doe@app.test'],
    ]);

    expect($userData->getAttribute('mail'))->toBe(['john@app.test', 'john.doe@app.test']);
});

it('returns only the first attribute value when requested', function () {
    $userData = makeSamlUserData([
        'mail' => ['john@app.test', 'john.doe@app.test'],
    ]);

    expect($userData->getAttribute('mail', onlyFirst: true))->toBe('john@app.test');
});

it('returns null for a missing attribute', function () {
    $userData = makeSamlUserData();

    expect($userData->getAttribute('missing'))->toBeNull()
        ->and($userData->getAttribute('missing', onlyFirst: true))->toBeNull();
});

it('reads attributes by friendly name', function () {
    $userData = makeSamlUserData(friendly: [
        'Email' => ['john@app.test'],
    ]);

    expect($userData->getAttributeWithFriendlyName('Email'))->toBe(['john@app.test'])
        ->and($userData->getAttributeWithFriendlyName('Email', onlyFirst: true))->toBe('john@app.test');
});
