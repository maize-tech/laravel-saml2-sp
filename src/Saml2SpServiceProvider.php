<?php

namespace Maize\Saml2Sp;

use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Utils;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Saml2SpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-saml2-sp')
            ->hasConfigFile()
            ->hasRoute('routes')
            ->hasMigration('create_laravel-saml2-sp_table');
    }

    public function packageBooted(): void
    {
        Utils::setProxyVars(
            Config::getProxyVarsEnabled()
        );
    }
}
