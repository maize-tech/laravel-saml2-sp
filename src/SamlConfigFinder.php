<?php

namespace Maize\Saml2Sp;

use Illuminate\Http\Request;
use Maize\Saml2Sp\Models\SamlConfig;

abstract class SamlConfigFinder
{
    abstract public static function findForRequest(Request $request): ?SamlConfig;
}
