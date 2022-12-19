<?php

namespace Maize\Saml2Sp;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Saml2SpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-saml2-sp')
            ->hasConfigFile()
            ->hasMigration('create_laravel-saml2-sp_table');
    }
}
