# Laravel SAML2 Service Provider

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-saml2-sp.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-saml2-sp)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-saml2-sp/run-tests.yml?branch=main&label=tests)](https://github.com/maize-tech/laravel-saml2-sp/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-saml2-sp/fix-php-code-style-issues.yml?branch=main&label=code%20style)](https://github.com/maize-tech/laravel-saml2-sp/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-saml2-sp.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-saml2-sp)

This package lets you add SAML2 authentication support within your application.

It acts as a SAML2 **Service Provider** (SP): it exposes the SP metadata, redirects
users to your Identity Provider (IdP) for authentication, consumes the IdP
assertion to log the user in, and handles single logout. Under the hood it wraps
[onelogin/php-saml](https://github.com/SAML-Toolkits/php-saml) and stores one or
more SAML configurations in the database.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-saml2-sp
```

You can publish the config and migration files and run the migrations with:

```bash
php artisan saml2-sp:install
```

This is the content of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | User model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the user model.
    |
    */

    'user_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the guard you want to use to authenticate the user.
    | The guard name must be defined in your application's auth.php config file.
    | When null, the default guard specified in 'auth.php' will be used.
    |
    */

    'auth_guard' => null,

    /*
    |--------------------------------------------------------------------------
    | Config model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the config model.
    |
    */

    'config_model' => Maize\Saml2Sp\Models\SamlConfig::class,

    /*
    |--------------------------------------------------------------------------
    | Config finder
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the config finder class.
    |
    */

    'config_finder' => Maize\Saml2Sp\DefaultSamlConfigFinder::class,

    /*
    |--------------------------------------------------------------------------
    | Enable proxy vars
    |--------------------------------------------------------------------------
    |
    | Here you may specify whether you want to enable proxy vars or not.
    | When true, the package will trust proxy headers such as
    | HTTP_X_FORWARDED_PROTO.
    | Useful when your application is running behind a load balancer.
    |
    */

    'proxy_vars_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Login return url
    |--------------------------------------------------------------------------
    |
    | Here you may specify the url where users should be redirected after login.
    | Used as default variable if no return url is specified in the login request.
    |
    */

    'login_return_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Logout return url
    |--------------------------------------------------------------------------
    |
    | Here you may specify the url where users should be redirected after logout.
    | Used as default variable if no return url is specified in the logout request.
    |
    */

    'logout_return_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Domain whitelist
    |--------------------------------------------------------------------------
    |
    | Here you may specify the list of whitelisted domains accepted as return url.
    | The package will only check for the first and second level domain excluding
    | the url schema and path.
    |
    */

    'domain_whitelist' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Route configurations
    |--------------------------------------------------------------------------
    |
    | Here you may specify whether routes should be enabled or not.
    | You can also customize the routes prefix and middlewares.
    |
    */

    'routes' => [
        'enabled' => true,
        'prefix' => 'saml2',
        'middleware' => ['web'],
    ],

    'actions' => [

        /*
        |--------------------------------------------------------------------------
        | Authenticate user
        |--------------------------------------------------------------------------
        |
        | Here you may specify the fully qualified class name of the auth action.
        | If needed, you may define your own action, which should override the
        | default one.
        |
        */

        'authenticate_user' => Maize\Saml2Sp\Actions\AuthenticateUser::class,

        /*
        |--------------------------------------------------------------------------
        | Logout user
        |--------------------------------------------------------------------------
        |
        | Here you may specify the fully qualified class name of the logout action.
        | If needed, you may define your own action, which should override the
        | default one.
        |
        */

        'logout_user' => Maize\Saml2Sp\Actions\LogoutUser::class,

    ],

    'default_values' => [

        /*
        |--------------------------------------------------------------------------
        | Strict mode
        |--------------------------------------------------------------------------
        |
        | Here you may specify whether the communication between the service and
        | identity providers should be validated or not.
        | When true, all requests with invalid data will be automatically rejected.
        |
        */

        'strict' => true,

        /*
        |--------------------------------------------------------------------------
        | Debug mode
        |--------------------------------------------------------------------------
        |
        | Here you may specify whether the debug mode is enabled or not.
        | When true, most authentication errors will be printed out.
        |
        */

        'debug' => false,

        /*
        |--------------------------------------------------------------------------
        | Organization values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default organization values in many languages.
        | When needed, you may include another translation following the ISO 639-1
        | standard language codes.
        |
        */

        'organization' => [
            'en-US' => [
                'url' => null,
                'name' => null,
                'displayname' => null,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Contact information values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default technical and support values.
        |
        */

        'contactPerson' => [
            'support' => [
                'givenName' => null,
                'emailAddress' => null,
            ],
            'technical' => [
                'givenName' => null,
                'emailAddress' => null,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Service provider values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default saml service provider values.
        |
        */

        'sp' => [
            'entityId' => null,
            'x509cert' => null,
            'privateKey' => null,
            'singleLogoutService' => [
                'url' => null,
            ],
            'assertionConsumerService' => [
                'url' => null,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Identity provider values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default saml identity provider values.
        |
        */

        'idp' => [
            'entityId' => null,
            'x509cert' => null,
            'singleLogoutService' => [
                'url' => null,
            ],
            'singleSignOnService' => [
                'url' => null,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Security values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default saml security values.
        |
        */

        'security' => [
            //
        ],

    ],

];
```

## Usage

### Minimum configuration

After publishing the config file, set at least the following keys in
`config/saml2-sp.php` (or via your environment):

- `user_model`: the fully qualified class name of your authenticatable user model.
- `login_return_url`: where users are redirected after a successful login.
- `logout_return_url`: where users are redirected after logout.
- `domain_whitelist`: the list of domains accepted as return urls. Any
  `return_url` / `RelayState` whose host is not whitelisted falls back to the
  configured return url. This prevents open-redirect attacks.

```php
'user_model' => App\Models\User::class,

'login_return_url' => 'https://my-app.test/dashboard',

'logout_return_url' => 'https://my-app.test/login',

'domain_whitelist' => [
    'my-app.test',
],
```

Both `login_return_url` and `logout_return_url` also accept a closure or the class
name of an invokable class, so you can resolve the destination at runtime:

```php
'login_return_url' => fn () => route('dashboard'),
```

### Creating a SAML configuration

SAML settings are stored in the database through the `SamlConfig` model. Each row
holds the service provider (`sp`) and identity provider (`idp`) sections, merged at
runtime with the `default_values` defined in the config file. The `sp`, `idp`,
`security`, `contactPerson` and `organization` columns are transparently encrypted.

```php
use Maize\Saml2Sp\Models\SamlConfig;

SamlConfig::create([
    'strict' => true,
    'debug' => false,
    'sp' => [
        'entityId' => 'https://my-app.test/saml2/metadata',
        'assertionConsumerService' => [
            'url' => 'https://my-app.test/saml2/acs',
        ],
        'singleLogoutService' => [
            'url' => 'https://my-app.test/saml2/sls',
        ],
        'x509cert' => '...your SP certificate...',
        'privateKey' => '...your SP private key...',
    ],
    'idp' => [
        'entityId' => 'https://idp.example.com/metadata',
        'singleSignOnService' => [
            'url' => 'https://idp.example.com/sso',
        ],
        'singleLogoutService' => [
            'url' => 'https://idp.example.com/slo',
        ],
        'x509cert' => '...your IdP certificate...',
    ],
]);
```

### Routes

When `routes.enabled` is `true` (the default), the package registers the following
routes under the configured prefix (`saml2` by default) and middleware (`web`):

| Method     | URI              | Name             | Description                                                          |
|------------|------------------|------------------|----------------------------------------------------------------------|
| `GET`      | `saml2/metadata` | `saml2.metadata` | Returns the SP metadata XML to share with your IdP.                  |
| `GET`      | `saml2/login`    | `saml2.login`    | Builds the authentication request and redirects to the IdP.         |
| `POST`     | `saml2/acs`      | `saml2.acs`      | Assertion Consumer Service: consumes the IdP response and logs in.  |
| `GET`      | `saml2/logout`   | `saml2.logout`   | Builds the logout request and redirects to the IdP.                 |
| `GET/POST` | `saml2/sls`      | `saml2.sls`      | Single Logout Service: logs the user out and redirects back.        |

A typical flow looks like this:

1. Share `saml2/metadata` with your Identity Provider.
2. Send the user to `saml2/login` (optionally with a whitelisted `?return_url=`).
   They are redirected to the IdP to authenticate.
3. The IdP posts the assertion back to `saml2/acs`. The package validates it,
   resolves the matching user and logs them in, then redirects to the
   `RelayState` (if whitelisted) or to `login_return_url`.
4. To log out, send the user to `saml2/logout`. After the IdP processes it, the
   `saml2/sls` endpoint logs the user out locally and redirects to
   `logout_return_url`.

You may disable the built-in routes (`routes.enabled => false`) and register your
own pointing to the package controllers if you need full control.

### Events

The package dispatches two events you can listen to:

- `Maize\Saml2Sp\Events\SamlLoggedIn` — after a user is authenticated through the
  ACS endpoint.
- `Maize\Saml2Sp\Events\SamlLoggedOut` — after a user is logged out through the
  SLS endpoint.

Both expose the affected user via a public `$user` property:

```php
use Maize\Saml2Sp\Events\SamlLoggedIn;

class NotifyUserLoggedIn
{
    public function handle(SamlLoggedIn $event): void
    {
        logger()->info('SAML login', ['id' => $event->user->getAuthIdentifier()]);
    }
}
```

### Customizing the authentication logic

The default `AuthenticateUser` action looks up the user by matching its `email`
column against the SAML `nameId`, then logs it in through the configured guard.
The default `LogoutUser` action logs out the currently authenticated user.

You can replace either action with your own by pointing the config to a custom
class:

```php
'actions' => [
    'authenticate_user' => App\Saml\AuthenticateUser::class,
    'logout_user' => App\Saml\LogoutUser::class,
],
```

A custom authenticate action receives the `Maize\Saml2Sp\SamlUserData` instance
(name id, attributes and friendly-name attributes) and must return an
`Illuminate\Contracts\Auth\Authenticatable`:

```php
use Illuminate\Contracts\Auth\Authenticatable;
use Maize\Saml2Sp\SamlUserData;

class AuthenticateUser
{
    public function __invoke(SamlUserData $userData): Authenticatable
    {
        $user = User::firstOrCreate(
            ['email' => $userData->nameId],
            ['name' => $userData->getAttribute('displayName', onlyFirst: true)],
        );

        auth()->login($user);

        return $user;
    }
}
```

### Resolving the configuration per request (multi-tenant)

By default the `DefaultSamlConfigFinder` returns the first `SamlConfig` row. If you
serve several Identity Providers (e.g. one per tenant or domain), implement your
own finder and register it in the config:

```php
use Illuminate\Http\Request;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlConfigFinder;

class TenantSamlConfigFinder extends SamlConfigFinder
{
    public static function findForRequest(Request $request): ?SamlConfig
    {
        return SamlConfig::query()
            ->where('tenant_id', $request->user()?->tenant_id)
            ->first();
    }
}
```

```php
'config_finder' => App\Saml\TenantSamlConfigFinder::class,
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/maize-tech/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/maize-tech/.github/security/policy) on how to report security vulnerabilities.

## Credits

- [Enrico De Lazzari](https://github.com/enricodelazzari)
- [Riccardo Dalla Via](https://github.com/riccardodallavia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
