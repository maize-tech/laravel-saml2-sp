<?php

use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\Actions\LogoutUser;
use Orchestra\Testbench\Factories\UserFactory;

it('logs out the authenticated user and returns it', function () {
    $user = UserFactory::new()->create();

    Auth::login($user);
    expect(Auth::check())->toBeTrue();

    $loggedOut = app(LogoutUser::class)();

    expect($loggedOut->is($user))->toBeTrue()
        ->and(Auth::check())->toBeFalse();
});
