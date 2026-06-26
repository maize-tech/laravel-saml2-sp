<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Maize\Saml2Sp\Http\Controllers\SamlAcsController;
use Maize\Saml2Sp\Http\Controllers\SamlLoginController;
use Maize\Saml2Sp\Http\Controllers\SamlLogoutController;
use Maize\Saml2Sp\Http\Controllers\SamlMetadataController;
use Maize\Saml2Sp\Http\Controllers\SamlSlsController;
use Maize\Saml2Sp\Support\Config;

if (Config::getRoutesEnabled()) {
    $prefix = Config::getRoutesPrefix();
    $middleware = Config::getRoutesMiddleware();
    $keyParameter = Config::getRoutesKeyParameter();

    // When serving multiple identity providers, prepend the route key segment
    // (e.g. saml2/{saml_config}/login) so the config finder can resolve it.
    $segment = $keyParameter ? '{'.$keyParameter.'}/' : '';

    Route::group([
        'prefix' => $prefix,
        'as' => Str::finish($prefix, '.'),
        'middleware' => $middleware,
    ], function () use ($segment) {
        Route::get($segment.'metadata', SamlMetadataController::class)->name('metadata');

        Route::get($segment.'login', SamlLoginController::class)->name('login');
        Route::post($segment.'acs', SamlAcsController::class)->name('acs');

        Route::get($segment.'logout', SamlLogoutController::class)->name('logout');
        Route::match(['GET', 'POST'], $segment.'sls', SamlSlsController::class)->name('sls');
    });
}
