<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maize\Saml2Sp\Facades\Saml2Sp;
use Maize\Saml2Sp\SamlAuth;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;
use Spatie\Url\Url;

abstract class SamlController extends Controller
{
    /**
     * @throws Error
     */
    protected function retrieveAuthManager(Request $request): SamlAuth
    {
        return Saml2Sp::auth($request);
    }

    protected function retrieveUrl(?string $url, string $defaultUrl): string
    {
        if (is_null($url) || ! Config::isDomainWhitelisted($url)) {
            $url = $defaultUrl;
        }

        return Url::fromString($url)->withScheme('https');
    }
}
