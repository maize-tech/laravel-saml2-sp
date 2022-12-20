<?php

namespace Maize\Saml2Sp\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Maize\Saml2Sp\SamlUserData;

class SamlLoggedIn
{
    use SerializesModels;
    use Dispatchable;

    public function __construct(
      public Authenticatable $user,
      public SamlUserData $userData
    ) {
    }
}
