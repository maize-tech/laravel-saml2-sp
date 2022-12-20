<?php

namespace Maize\Saml2Sp;

use Illuminate\Http\Request;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\Support\Config;

class DefaultSamlConfigFinder extends SamlConfigFinder
{
    public static function findForRequest(Request $request): ?SamlConfig
    {
        /** @var SamlConfig */
        return Config::getSamlConfigModel()
            ->query()
            ->first();
    }
}
