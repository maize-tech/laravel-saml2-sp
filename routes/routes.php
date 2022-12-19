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

    Route::group([
        'prefix' => $prefix,
        'as' => Str::finish($prefix, '.'),
        'middleware' => $middleware,
    ], function () {
        Route::get('metadata', SamlMetadataController::class)->name('metadata');

        Route::get('login', SamlLoginController::class)->name('login');
        Route::post('acs', SamlAcsController::class)->name('acs');

        Route::get('logout', SamlLogoutController::class)->name('logout');
        Route::match(['GET', 'POST'], 'sls', SamlSlsController::class)->name('sls');
    });
}
