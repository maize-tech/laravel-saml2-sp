<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Maize\Saml2Sp\Http\Requests\SamlLogoutRequest;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;

class SamlLogoutController extends SamlController
{
    /**
     * @throws Error
     */
    public function __invoke(SamlLogoutRequest $request)
    {
        $returnUrl = $this->retrieveUrl(
            $request->get('return_url'),
            Config::getLogoutReturnURL()
        );

        return $this
            ->retrieveAuthManager($request)
            ->logout(
                returnTo: $returnUrl
            );
    }
}
