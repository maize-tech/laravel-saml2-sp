<?php

return [

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
