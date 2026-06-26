<?php

namespace Maize\Saml2Sp\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Maize\Saml2Sp\Models\SamlConfig;

class SamlLoggedOut
{
    use Dispatchable;

    public function __construct(
        public readonly Authenticatable $user,
        public readonly ?SamlConfig $config = null,
    ) {
        //
    }
}
