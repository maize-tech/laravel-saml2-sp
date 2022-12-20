<?php

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
    |
    */

    'auth_guard' => 'web',

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
        'authenticate_user' => Maize\Saml2SP\Actions\AuthenticateUser::class,
    ],

    'default_values' => [

        /*
        |--------------------------------------------------------------------------
        | Service provider values
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default saml service provider values.
        | The package will merge those configs with the ones found within `SamlConfig`.
        |
        */

        'sp' => [
            'debug' => false,
            'strict' => true,
            'entityId' => null,
            'x509cert' => null,
            'privateKey' => null,
            'organization' => [
                'url' => null,
                'name' => null,
                'displayname' => null,
            ],
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
        | The package will merge those configs with the ones found within `SamlConfig`.
        |
        */

        'idp' => [
            'entityId' => null,
            'x509cert' => null,
            'attributeSchema' => [
                'email' => null,
            ],
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
        | The package will merge those configs with the ones found within `SamlConfig`.
        |
        */

        'security' => [
            //
        ],

    ],

];
