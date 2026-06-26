<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\RouteKeySamlConfigFinder;

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

function requestWithKey(string $key): Request
{
    $request = Request::create("https://sp.test/saml2/{$key}/login");

    $request->setRouteResolver(
        fn () => (new Route('GET', 'saml2/{saml_config}/login', []))->bind($request)
    );

    return $request;
}

it('resolves the saml config by its route key', function () {
    $acme = SamlConfig::factory()->create(['key' => 'acme']);
    SamlConfig::factory()->create(['key' => 'other']);

    $found = RouteKeySamlConfigFinder::findForRequest(requestWithKey('acme'));

    expect($found)->toBeInstanceOf(SamlConfig::class)
        ->and($found->is($acme))->toBeTrue();
});

it('returns null when the route key does not match any saml config', function () {
    SamlConfig::factory()->create(['key' => 'acme']);

    expect(RouteKeySamlConfigFinder::findForRequest(requestWithKey('missing')))->toBeNull();
});

it('returns null when no route key is present', function () {
    expect(RouteKeySamlConfigFinder::findForRequest(new Request))->toBeNull();
});
