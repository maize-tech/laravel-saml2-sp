<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\Actions\AuthenticateUser;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlError;
use Maize\Saml2Sp\SamlUserData;
use OneLogin\Saml2\Settings;
use Orchestra\Testbench\Factories\UserFactory;

function samlUserDataFor(string $nameId, array $attributes = []): SamlUserData
{
    return new SamlUserData(
        settings: new Settings(SamlConfig::factory()->make()->settings),
        attributes: $attributes,
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

it('throws a saml error when no user matches and jit is disabled', function () {
    UserFactory::new()->create(['email' => 'someone@app.test']);

    app(AuthenticateUser::class)(samlUserDataFor('missing@app.test'));
})->throws(SamlError::class);

it('uses the configured auth guard', function () {
    config()->set('saml2-sp.auth_guard', 'web');

    $user = UserFactory::new()->create(['email' => 'jane@app.test']);

    app(AuthenticateUser::class)(samlUserDataFor('jane@app.test'));

    expect(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($user->getKey());
});

it('resolves the identifier from a configured saml attribute', function () {
    config()->set('saml2-sp.user_identifier.saml_attribute', 'uid');

    $user = UserFactory::new()->create(['email' => 'attr@app.test']);

    $authenticated = app(AuthenticateUser::class)(samlUserDataFor('ignored-name-id', [
        'uid' => ['attr@app.test'],
    ]));

    expect($authenticated->is($user))->toBeTrue();
});

it('looks the user up by a configured column', function () {
    config()->set('saml2-sp.user_identifier.column', 'name');

    $user = UserFactory::new()->create(['name' => 'jdoe']);

    $authenticated = app(AuthenticateUser::class)(samlUserDataFor('jdoe'));

    expect($authenticated->is($user))->toBeTrue();
});

it('creates the user on first login when jit provisioning is enabled', function () {
    config()->set('saml2-sp.user_model', JitUser::class);
    config()->set('saml2-sp.jit_provisioning.enabled', true);
    config()->set('saml2-sp.jit_provisioning.attribute_map', [
        'name' => 'displayName',
        'password' => 'token',
    ]);

    expect(JitUser::query()->count())->toBe(0);

    $authenticated = app(AuthenticateUser::class)(samlUserDataFor('new@app.test', [
        'displayName' => ['New User'],
        'token' => ['secret'],
    ]));

    expect(JitUser::query()->count())->toBe(1)
        ->and($authenticated->getAttribute('email'))->toBe('new@app.test')
        ->and($authenticated->getAttribute('name'))->toBe('New User')
        ->and(Auth::check())->toBeTrue();
});

class JitUser extends User
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];
}
