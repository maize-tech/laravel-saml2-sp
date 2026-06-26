<?php

use Maize\Saml2Sp\Actions\AuthenticateUser;
use Maize\Saml2Sp\Actions\LogoutUser;
use Maize\Saml2Sp\DefaultSamlConfigFinder;
use Maize\Saml2Sp\Models\SamlConfig;

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
    | User identifier
    |--------------------------------------------------------------------------
    |
    | Here you may specify how the authenticated user is resolved from the SAML
    | response. The package looks up your user model using the given 'column'.
    | When 'saml_attribute' is null the nameId is used as the lookup value,
    | otherwise the given SAML attribute (first value) is used instead.
    |
    */

    'user_identifier' => [
        'column' => 'email',
        'saml_attribute' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Just-in-time (JIT) provisioning
    |--------------------------------------------------------------------------
    |
    | Here you may enable just-in-time user provisioning. When enabled, a user
    | that does not yet exist is created on its first successful login.
    | The 'attribute_map' maps your user model columns to SAML attributes, e.g.
    | ['name' => 'displayName']. The identifier column is always filled with the
    | resolved identifier value.
    |
    */

    'jit_provisioning' => [
        'enabled' => false,
        'attribute_map' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Config model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the config model.
    |
    */

    'config_model' => SamlConfig::class,

    /*
    |--------------------------------------------------------------------------
    | Config finder
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the config finder class.
    |
    */

    'config_finder' => DefaultSamlConfigFinder::class,

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
    | Error return url
    |--------------------------------------------------------------------------
    |
    | Here you may specify the url where users should be redirected when a SAML
    | error occurs (e.g. an invalid assertion) while debug mode is disabled.
    | When null, the logout return url is used as fallback. The error message
    | is flashed to the session under the 'saml2-sp.error' key.
    |
    */

    'error_return_url' => null,

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
    | When serving multiple identity providers, set 'key_parameter' to a route
    | parameter name (e.g. 'saml_config'). The package routes then include that
    | segment (e.g. saml2/{saml_config}/login) so a config finder such as the
    | RouteKeySamlConfigFinder can resolve the right SamlConfig per request.
    |
    */

    'routes' => [
        'enabled' => true,
        'prefix' => 'saml2',
        'middleware' => ['web'],
        'key_parameter' => null,
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

        'authenticate_user' => AuthenticateUser::class,

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

        'logout_user' => LogoutUser::class,

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
