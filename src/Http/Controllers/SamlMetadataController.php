<?php

namespace Maize\Saml2Sp\Http\Controllers;

use Illuminate\Http\Request;
use OneLogin\Saml2\Error;

class SamlMetadataController extends SamlController
{
    /**
     * @throws Error
     */
    public function __invoke(Request $request)
    {
        $metadata = $this
            ->retrieveAuthManager($request)
            ->getMetadata();

        return response($metadata)
            ->header('Content-Type', 'text/xml');
    }
}
