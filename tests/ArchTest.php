<?php

arch('it does not use debugging helpers')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('it does not leave debugging statements in the source')
    ->expect('Maize\Saml2Sp')
    ->not->toUse(['die', 'exit']);
