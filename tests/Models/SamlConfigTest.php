<?php

use Maize\Saml2Sp\Models\SamlConfig;

it('persists and casts the encrypted array columns', function () {
    $config = SamlConfig::factory()->create([
        'sp' => ['entityId' => 'https://sp.test/metadata'],
        'idp' => ['entityId' => 'https://idp.test/metadata'],
        'security' => ['wantMessagesSigned' => true],
    ]);

    // Stored ciphertext must not equal the plaintext value.
    $raw = $config->getRawOriginal('sp');
    expect($raw)->not->toContain('https://sp.test/metadata');

    $fresh = SamlConfig::query()->find($config->getKey());

    expect($fresh->sp)->toBe(['entityId' => 'https://sp.test/metadata'])
        ->and($fresh->idp)->toBe(['entityId' => 'https://idp.test/metadata'])
        ->and($fresh->security)->toBe(['wantMessagesSigned' => true]);
});

it('casts the boolean columns', function () {
    $config = SamlConfig::factory()->strict()->debug()->create();

    expect($config->strict)->toBeTrue()
        ->and($config->debug)->toBeTrue();
});

it('builds the merged settings from model values and config defaults', function () {
    config()->set('saml2-sp.default_values.strict', true);
    config()->set('saml2-sp.default_values.debug', false);
    config()->set('saml2-sp.default_values.security', ['signMetadata' => false]);

    $config = SamlConfig::factory()->create([
        'security' => ['wantMessagesSigned' => true],
    ]);

    $settings = $config->settings;

    expect($settings)->toBeArray()
        ->toHaveKeys(['strict', 'debug', 'sp', 'idp', 'security', 'contactPerson', 'organization'])
        ->and($settings['security'])->toBe([
            'signMetadata' => false,
            'wantMessagesSigned' => true,
        ]);
});

it('drops the organization and contact placeholders left empty in the config defaults', function () {
    $config = SamlConfig::factory()->make([
        'contactPerson' => [],
        'organization' => [],
    ]);

    $settings = $config->settings;

    expect($settings['contactPerson'])->toBe([])
        ->and($settings['organization'])->toBe([]);
});

it('keeps the organization and contacts that are filled in', function () {
    config()->set('saml2-sp.default_values.contactPerson.support', [
        'givenName' => 'Support',
        'emailAddress' => 'support@sp.test',
    ]);

    $config = SamlConfig::factory()->make([
        'organization' => [
            'en-US' => [
                'url' => 'https://sp.test',
                'name' => 'SP',
                'displayname' => 'Service Provider',
            ],
        ],
    ]);

    $settings = $config->settings;

    expect($settings['contactPerson'])->toBe([
        'support' => [
            'givenName' => 'Support',
            'emailAddress' => 'support@sp.test',
        ],
    ])->and($settings['organization'])->toBe([
        'en-US' => [
            'url' => 'https://sp.test',
            'name' => 'SP',
            'displayname' => 'Service Provider',
        ],
    ]);
});

it('keeps partially filled contacts so that php-saml can report them', function () {
    $config = SamlConfig::factory()->make([
        'contactPerson' => [
            'technical' => ['givenName' => 'Tech'],
        ],
    ]);

    expect($config->settings['contactPerson'])->toBe([
        'technical' => [
            'givenName' => 'Tech',
            'emailAddress' => null,
        ],
    ]);
});
