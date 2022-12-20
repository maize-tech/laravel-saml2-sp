<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maize\Saml2Sp\SamlAuth;
use Maize\Saml2Sp\SamlError;
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
        $samlConfig = Config::getSamlConfigFinder()
            ->findForRequest($request);

        if (! $samlConfig) {
            throw new SamlError(
                msg: 'Settings file not found',
                code: SamlError::SETTINGS_FILE_NOT_FOUND
            );
        }

        return new SamlAuth($samlConfig);
    }

    protected function retrieveUrl(?string $url, string $defaultUrl): ?string
    {
        $url ??= $defaultUrl;
        $url = Url::fromString($url)->withScheme('https');

        if (! Config::isDomainWhitelisted($url)) {
            return null;
        }

        return $url;
    }
}
