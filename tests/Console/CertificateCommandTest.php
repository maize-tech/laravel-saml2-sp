<?php

it('generates a self-signed certificate and private key', function () {
    $this->artisan('saml2-sp:certificate', ['--cn' => 'sp.test', '--days' => 30])
        ->expectsOutputToContain('BEGIN CERTIFICATE')
        ->expectsOutputToContain('PRIVATE KEY')
        ->assertSuccessful();
})->skip(
    ! extension_loaded('openssl'),
    'The openssl extension is not available.'
);
