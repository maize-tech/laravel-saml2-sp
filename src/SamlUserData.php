<?php

namespace Maize\Saml2Sp;

use OneLogin\Saml2\Settings;

readonly class SamlUserData
{
    public function __construct(
        public Settings $settings,
        public array $attributes,
        public array $attributesWithFriendlyName,
        public string $nameId,
        public string $nameIdFormat,
        public string $nameIdNameQualifier,
        public string $nameIdSPNameQualifier,
    ) {
        //
    }

    public function getAttribute($name): ?array
    {
        return data_get($this->attributes, $name);
    }

    public function getAttributeWithFriendlyName($name): ?array
    {
        return data_get($this->attributesWithFriendlyName, $name);
    }
}
