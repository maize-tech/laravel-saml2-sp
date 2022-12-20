<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Maize\Saml2Sp\Events\SamlLoggedOut;
use Maize\Saml2Sp\Http\Requests\SamlSlsRequest;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;

class SamlSlsController extends SamlController
{
    /**
     * @throws Error
     * @throws Exception
     */
    public function __invoke(SamlSlsRequest $request): Redirector|RedirectResponse
    {
        $destination = $this->retrieveUrl(
            $request->get('RelayState'),
            Config::getLogoutReturnURL()
        );

        $samlAuth = $this->retrieveAuthManager($request);
        $samlAuth->sls();

        $user = app(Config::getLogoutUserAction());

        SamlLoggedOut::dispatch($user);

        return redirect($destination);
    }
}
