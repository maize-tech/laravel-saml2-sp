<?php

namespace Maize\Saml2Sp;

use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Utils;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
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
            ->hasMigration('create_saml_configs_table')
            ->hasInstallCommand(
                fn (InstallCommand $command) => $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('maize-tech/laravel-saml2-sp')
            );
    }

    public function packageBooted(): void
    {
        Utils::setProxyVars(
            Config::getProxyVarsEnabled()
        );
    }
}
