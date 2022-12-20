<?php

namespace Maize\Saml2Sp\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SamlLoggedOut
{
    use SerializesModels;
    use Dispatchable;

    public function __construct(
      public Authenticatable $user
    ) {
        //
    }
}
