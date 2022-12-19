<?php

namespace Maize\Saml2Sp\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;
use Maize\Saml2Sp\Support\Config;

class SamlAttributeCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): array
    {
        if (! is_null($value)) {
            $value = Crypt::decryptString($value);
        }

        $defaultValue = Config::getDefaultSamlValues($key);
        $value = json_decode($value, true) ?? [];

        return array_replace_recursive($defaultValue, $value);
    }

    public function set($model, $key, $value, $attributes): string
    {
        return Crypt::encryptString(
            json_encode($value)
        );
    }
}
