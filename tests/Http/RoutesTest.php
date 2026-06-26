<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Maize\Saml2Sp\Events\SamlLoggedOut;
use Maize\Saml2Sp\Models\SamlConfig;
use OneLogin\Saml2\Error;
use Orchestra\Testbench\Factories\UserFactory;

it('registers the package routes', function () {
    expect(Route::has('saml2.metadata'))->toBeTrue()
        ->and(Route::has('saml2.login'))->toBeTrue()
        ->and(Route::has('saml2.acs'))->toBeTrue()
        ->and(Route::has('saml2.logout'))->toBeTrue()
        ->and(Route::has('saml2.sls'))->toBeTrue();
});

it('serves the sp metadata as xml', function () {
    SamlConfig::factory()->create();

    $response = $this->get(route('saml2.metadata'));

    $response->assertOk();

    // The charset casing varies across environments, so match loosely.
    expect($response->headers->get('Content-Type'))->toContain('text/xml');

    expect($response->getContent())
        ->toContain('https://sp.test/saml2/metadata')
        ->toContain('EntityDescriptor');
});

it('fails the acs endpoint without a valid saml response', function () {
    SamlConfig::factory()->create();

    $this->withoutExceptionHandling();

    $this->post(route('saml2.acs'));
})->throws(Error::class);

it('logs the user out and redirects on the sls endpoint', function () {
    Event::fake([SamlLoggedOut::class]);

    SamlConfig::factory()->create();
    $user = UserFactory::new()->create();

    $response = $this->actingAs($user)->get(route('saml2.sls'));

    $response->assertRedirect('https://app.test/login');

    expect(auth()->check())->toBeFalse();

    Event::assertDispatched(
        SamlLoggedOut::class,
        fn (SamlLoggedOut $event) => $event->user->is($user)
    );
});

it('falls back to the configured return url for a non-whitelisted relay state', function () {
    Event::fake([SamlLoggedOut::class]);

    SamlConfig::factory()->create();
    $user = UserFactory::new()->create();

    $response = $this->actingAs($user)->get(route('saml2.sls', [
        'RelayState' => 'https://evil.example/phishing',
    ]));

    $response->assertRedirect('https://app.test/login');
});

it('honours a whitelisted relay state on the sls endpoint', function () {
    Event::fake([SamlLoggedOut::class]);

    SamlConfig::factory()->create();
    $user = UserFactory::new()->create();

    $response = $this->actingAs($user)->get(route('saml2.sls', [
        'RelayState' => 'https://app.test/custom-logout',
    ]));

    $response->assertRedirect('https://app.test/custom-logout');
});
