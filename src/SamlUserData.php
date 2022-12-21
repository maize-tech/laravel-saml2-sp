<?php

namespace Maize\Saml2Sp;

use Illuminate\Support\Arr;
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

    public function getAttribute(string $name, bool $onlyFirst = false): mixed
    {
        $attribute = data_get($this->attributes, $name);

        if ($onlyFirst) {
            return Arr::first($attribute);
        }

        return $attribute;
    }

    public function getAttributeWithFriendlyName(string $name, bool $onlyFirst = false): mixed
    {
        $attribute = data_get($this->attributesWithFriendlyName, $name);

        if ($onlyFirst) {
            return Arr::first($attribute);
        }

        return $attribute;
    }
}
