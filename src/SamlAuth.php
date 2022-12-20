<?php

namespace Maize\Saml2Sp;

use Exception;
use Maize\Saml2Sp\Models\SamlConfig;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Error;
use OneLogin\Saml2\ValidationError;

class SamlAuth
{
    private Auth $auth;

    private bool $debug;

    /**
     * @throws Error
     */
    public function __construct(SamlConfig $samlConfig)
    {
        $this->auth = new Auth(
            $samlConfig->settings
        );

        $this->debug = data_get(
            $samlConfig->settings,
            'sp.debug',
            false
        );
    }

    public function getSamlUser(): SamlUserData
    {
        return new SamlUserData(
            settings: $this->auth->getSettings(),
            attributes: $this->auth->getAttributes(),
            attributesWithFriendlyName: $this->auth->getAttributesWithFriendlyName(),
            nameId: $this->auth->getNameId(),
            nameIdFormat: $this->auth->getNameIdFormat(),
            nameIdNameQualifier: $this->auth->getNameIdNameQualifier(),
            nameIdSPNameQualifier: $this->auth->getNameIdSPNameQualifier()
        );
    }

    /**
     * @throws Error
     * @throws Exception
     */
    public function getMetadata(): string
    {
        $settings = $this->auth->getSettings();
        $metadata = $settings->getSPMetadata();
        $errors = $settings->validateMetadata($metadata);

        if (empty($errors)) {
            return $metadata;
        }

        throw new SamlError(
            'Invalid SP metadata: %s',
            SamlError::METADATA_SP_INVALID,
            [implode(', ', $errors)]
        );
    }

    /**
     * @throws Error
     */
    public function login(
        ?string $returnTo = null,
        array $parameters = [],
        bool $forceAuthn = false,
        bool $isPassive = false,
        bool $stay = false,
        bool $setNameIdPolicy = true,
        ?string $nameIdValueReq = null
    ): ?string {
        return $this->auth->login(
            $returnTo,
            $parameters,
            $forceAuthn,
            $isPassive,
            $stay,
            $setNameIdPolicy,
            $nameIdValueReq
        );
    }

    /**
     * @throws Error
     */
    public function logout(
        ?string $returnTo = null,
        array $parameters = [],
        ?string $nameId = null,
        ?string $sessionIndex = null,
        bool $stay = false,
        ?string $nameIdFormat = null,
        ?string $nameIdNameQualifier = null,
        ?string $nameIdSPNameQualifier = null
    ): ?string {
        return $this->auth->logout(
            $returnTo,
            $parameters,
            $nameId,
            $sessionIndex,
            $stay,
            $nameIdFormat,
            $nameIdNameQualifier,
            $nameIdSPNameQualifier
        );
    }

    /**
     * @throws ValidationError
     * @throws Error
     */
    public function acs(): self
    {
        $this->auth->processResponse();

        $errors = $this->auth->getErrors();

        if (empty($errors)) {
            return $this;
        }

        if (! $this->auth->isAuthenticated()) {
            $errors = ['unauthenticated' => 'Could not authenticate user'];
        }

        if (! $this->debug) {
            throw new SamlError(
                'Invalid acs response.',
                SamlError::SAML_ACS_INVALID
            );
        }

        throw new SamlError(
            'Invalid acs response: %s',
            SamlError::SAML_ACS_INVALID,
            [implode(', ', $errors)]
        );
    }
}
