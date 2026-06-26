<?php

namespace Maize\Saml2Sp\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Throwable;

class SamlLoginFailed
{
    use Dispatchable;

    public function __construct(
        public readonly Throwable $exception,
        public readonly Request $request,
    ) {
        //
    }
}
