<?php

namespace Maize\Saml2Sp;

use Maize\Saml2Sp\Console\CertificateCommand;
use Maize\Saml2Sp\Console\MetadataCommand;
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
            ->hasMigration('add_key_to_saml_configs_table')
            ->hasCommands([
                MetadataCommand::class,
                CertificateCommand::class,
            ])
            ->hasInstallCommand(
                fn (InstallCommand $command) => $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('maize-tech/laravel-saml2-sp')
            );
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Saml2Sp::class);
        $this->app->alias(Saml2Sp::class, 'saml2-sp');
    }

    public function packageBooted(): void
    {
        Utils::setProxyVars(
            Config::getProxyVarsEnabled()
        );
    }
}
