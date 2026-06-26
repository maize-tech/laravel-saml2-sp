<?php

namespace Maize\Saml2Sp;

use Illuminate\Http\Request;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\Support\Config;

class RouteKeySamlConfigFinder extends SamlConfigFinder
{
    public static function findForRequest(Request $request): ?SamlConfig
    {
        $parameter = Config::getRoutesKeyParameter() ?? 'saml_config';

        $key = $request->route($parameter);

        if (blank($key)) {
            return null;
        }

        /** @var SamlConfig|null */
        return Config::getSamlConfigModel()
            ->query()
            ->where('key', $key)
            ->first();
    }
}
