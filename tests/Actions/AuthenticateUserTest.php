<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\Actions\AuthenticateUser;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlUserData;
use OneLogin\Saml2\Settings;
use Orchestra\Testbench\Factories\UserFactory;

function samlUserDataFor(string $nameId): SamlUserData
{
    return new SamlUserData(
        settings: new Settings(SamlConfig::factory()->make()->settings),
        attributes: [],
        attributesWithFriendlyName: [],
        nameId: $nameId,
        nameIdFormat: null,
        nameIdNameQualifier: null,
        nameIdSPNameQualifier: null,
    );
}

it('finds the user by name id and logs it in', function () {
    $user = UserFactory::new()->create(['email' => 'john@app.test']);

    $authenticated = app(AuthenticateUser::class)(samlUserDataFor('john@app.test'));

    expect($authenticated->is($user))->toBeTrue()
        ->and(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($user->getKey());
});

it('throws when no user matches the name id', function () {
    UserFactory::new()->create(['email' => 'someone@app.test']);

    app(AuthenticateUser::class)(samlUserDataFor('missing@app.test'));
})->throws(ModelNotFoundException::class);

it('uses the configured auth guard', function () {
    config()->set('saml2-sp.auth_guard', 'web');

    $user = UserFactory::new()->create(['email' => 'jane@app.test']);

    app(AuthenticateUser::class)(samlUserDataFor('jane@app.test'));

    expect(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($user->getKey());
});
