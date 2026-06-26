<?php

namespace Maize\Saml2Sp\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Maize\Saml2Sp\Saml2SpServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Maize\\Saml2Sp\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            Saml2SpServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // The SamlConfig model relies on encrypted casts, so an app key is required.
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Default user model used by the authentication actions.
        config()->set('auth.providers.users.model', User::class);

        config()->set('saml2-sp.user_model', User::class);
        config()->set('saml2-sp.login_return_url', 'https://app.test/home');
        config()->set('saml2-sp.logout_return_url', 'https://app.test/login');
        config()->set('saml2-sp.domain_whitelist', ['app.test']);

        // OneLogin rejects partially-filled contact/organization blocks, so the
        // test environment leaves them empty (as a minimal deployment would).
        config()->set('saml2-sp.default_values.contactPerson', []);
        config()->set('saml2-sp.default_values.organization', []);
    }

    protected function defineDatabaseMigrations(): void
    {
        // Minimal "users" table used by the authentication actions and the
        // Testbench UserFactory.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $migration = include __DIR__.'/../database/migrations/create_saml_configs_table.php.stub';
        $migration->up();
    }
}
