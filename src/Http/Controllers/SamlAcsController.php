<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Maize\Saml2Sp\Events\SamlLoggedIn;
use Maize\Saml2Sp\Http\Requests\SamlAcsRequest;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;
use OneLogin\Saml2\ValidationError;

class SamlAcsController extends SamlController
{
    /**
     * @throws Error
     * @throws Exception
     * @throws ValidationError
     */
    public function __invoke(SamlAcsRequest $request): Redirector|RedirectResponse
    {
        $destination = $this->retrieveUrl(
            $request->get('RelayState'),
            Config::getLoginReturnURL()
        );

        $samlAuth = $this->retrieveAuthManager($request);
        $samlAuth->acs();

        $userData = $samlAuth->getSamlUser();
        $user = app(Config::getAuthenticateUserAction(), [
            $userData,
        ]);

        SamlLoggedIn::dispatch($user);

        return redirect($destination);
    }
}
