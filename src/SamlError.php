<?php

namespace Maize\Saml2Sp;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maize\Saml2Sp\Events\SamlLoginFailed;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;

class SamlError extends Error
{
    const SAML_ACS_INVALID = 100;

    const SAML_SLS_INVALID = 101;

    const SAML_USER_NOT_FOUND = 102;

    /**
     * Render the exception into an HTTP response.
     *
     * A SamlLoginFailed event is always dispatched. When the application is not
     * in debug mode, the user is redirected to the configured error return url
     * with the error message flashed under the 'saml2-sp.error' session key.
     * In debug mode the exception bubbles up to the default handler.
     */
    public function render(Request $request): ?RedirectResponse
    {
        SamlLoginFailed::dispatch($this, $request);

        if (config('app.debug')) {
            return null;
        }

        return redirect(Config::getErrorReturnURL())
            ->with('saml2-sp.error', $this->getMessage());
    }
}
