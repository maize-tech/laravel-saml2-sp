<?php

use Illuminate\Http\Request;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;

it('returns null when no saml config exists', function () {
    expect(DefaultSamlConfigFinder::findForRequest(new Request))->toBeNull();
});

it('returns the first stored saml config', function () {
    $first = SamlConfig::factory()->create();
    SamlConfig::factory()->create();

    $found = DefaultSamlConfigFinder::findForRequest(new Request);

    expect($found)->toBeInstanceOf(SamlConfig::class)
        ->and($found->is($first))->toBeTrue();
});
