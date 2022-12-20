<?php

namespace Maize\Saml2Sp\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;

class SamlLoggedOut
{
    use Dispatchable;

    public function __construct(
      public Authenticatable $user
    ) {
        //
    }
}
