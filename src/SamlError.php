<?php

namespace Maize\Saml2SP;

use OneLogin\Saml2\Error;

class SamlError extends Error
{
    const SAML_ACS_INVALID = 100;

    const SAML_SLS_INVALID = 101;
}
