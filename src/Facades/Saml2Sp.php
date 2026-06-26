<?php

namespace Maize\Saml2Sp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Maize\Saml2Sp\Models\SamlConfig|null config(\Illuminate\Http\Request|null $request = null)
 * @method static \Maize\Saml2Sp\SamlAuth auth(\Maize\Saml2Sp\Models\SamlConfig|\Illuminate\Http\Request|null $config = null)
 * @method static string metadata(\Maize\Saml2Sp\Models\SamlConfig|\Illuminate\Http\Request|null $config = null)
 * @method static string|null loginUrl(\Maize\Saml2Sp\Models\SamlConfig|\Illuminate\Http\Request|null $config = null, string|null $returnTo = null)
 * @method static string|null logoutUrl(\Maize\Saml2Sp\Models\SamlConfig|\Illuminate\Http\Request|null $config = null, string|null $returnTo = null)
 *
 * @see \Maize\Saml2Sp\Saml2Sp
 */
class Saml2Sp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Maize\Saml2Sp\Saml2Sp::class;
    }
}
