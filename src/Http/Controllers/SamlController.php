<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maize\Saml2Sp\SamlAuth;
use Maize\Saml2SP\SamlError;
use Maize\Saml2Sp\Support\Config;
use Maize\Saml2Sp\Support\UrlUtils;
use OneLogin\Saml2\Error;

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

    protected function retrieveUrl(?string $url, ?string $defaultUrl = null): ?string
    {
        $url = UrlUtils::sanitizeUrl($url);
        $defaultUrl = UrlUtils::sanitizeUrl($defaultUrl);

        if (is_null($url)) {
            return $defaultUrl;
        }

        if (Config::isDomainWhitelisted($url)) {
            return $url;
        }

        return $defaultUrl;
    }
}
