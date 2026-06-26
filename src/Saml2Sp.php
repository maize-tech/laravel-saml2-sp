<?php

namespace Maize\Saml2Sp;

use Illuminate\Http\Request;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\Support\Config;
use OneLogin\Saml2\Error;

class Saml2Sp
{
    /**
     * Resolve the SamlConfig for the given (or current) request using the
     * configured config finder.
     */
    public function config(?Request $request = null): ?SamlConfig
    {
        return Config::getSamlConfigFinder()->findForRequest(
            $request ?? request()
        );
    }

    /**
     * Build a SamlAuth instance for the given config. When a request (or null)
     * is given, the config is resolved through the configured finder.
     *
     * @throws Error
     */
    public function auth(SamlConfig|Request|null $config = null): SamlAuth
    {
        $samlConfig = $config instanceof SamlConfig
            ? $config
            : $this->config($config);

        if (is_null($samlConfig)) {
            throw new SamlError(
                msg: 'Settings file not found',
                code: SamlError::SETTINGS_FILE_NOT_FOUND
            );
        }

        return new SamlAuth($samlConfig);
    }

    /**
     * Generate the service provider metadata XML.
     *
     * @throws Error
     */
    public function metadata(SamlConfig|Request|null $config = null): string
    {
        return $this->auth($config)->getMetadata();
    }

    /**
     * Build the identity provider login URL without redirecting.
     *
     * @throws Error
     */
    public function loginUrl(SamlConfig|Request|null $config = null, ?string $returnTo = null): ?string
    {
        return $this->auth($config)->login(
            returnTo: $returnTo,
            stay: true,
        );
    }

    /**
     * Build the identity provider logout URL without redirecting.
     *
     * @throws Error
     */
    public function logoutUrl(SamlConfig|Request|null $config = null, ?string $returnTo = null): ?string
    {
        return $this->auth($config)->logout(
            returnTo: $returnTo,
            stay: true,
        );
    }
}
