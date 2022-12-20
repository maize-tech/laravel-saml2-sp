<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Maize\Saml2Sp\Http\Requests\SamlLoginRequest;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;

class SamlLoginController extends SamlController
{
    /**
     * @throws Error
     */
    public function __invoke(SamlLoginRequest $request): void
    {
        $returnUrl = $this->retrieveUrl(
            $request->get('return_url'),
            Config::getLoginReturnURL()
        );

        $this
            ->retrieveAuthManager($request)
            ->login(
                returnTo: $returnUrl
            );
    }
}
