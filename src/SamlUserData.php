<?php

namespace Maize\Saml2Sp;

use OneLogin\Saml2\Settings;

class SamlUserData
{
    public function __construct(
        public readonly Settings $settings,
        public readonly array $attributes,
        public readonly array $attributesWithFriendlyName,
        public readonly ?string $nameId,
        public readonly ?string $nameIdFormat,
        public readonly ?string $nameIdNameQualifier,
        public readonly ?string $nameIdSPNameQualifier,
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
