<?php

use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\SamlAuth;
use Maize\Saml2Sp\SamlError;
use OneLogin\Saml2\Error;

it('builds an auth instance from a valid saml config', function () {
    $auth = new SamlAuth(SamlConfig::factory()->make());

    expect($auth)->toBeInstanceOf(SamlAuth::class);
});

it('throws when the saml config is invalid', function () {
    $config = SamlConfig::factory()->make([
        'sp' => ['entityId' => null],
        'idp' => ['entityId' => null],
    ]);

    new SamlAuth($config);
})->throws(Error::class);

it('generates valid sp metadata containing the entity id', function () {
    $auth = new SamlAuth(SamlConfig::factory()->make());

    $metadata = $auth->getMetadata();

    expect($metadata)->toContain('https://sp.test/saml2/metadata')
        ->and($metadata)->toContain('AssertionConsumerService')
        ->and($metadata)->toContain('<?xml');
});

it('builds a login redirect url towards the idp sso endpoint', function () {
    $auth = new SamlAuth(SamlConfig::factory()->make());

    $url = $auth->login(returnTo: 'https://app.test/home', stay: true);

    expect($url)->toStartWith('https://idp.test/saml2/sso')
        ->and($url)->toContain('SAMLRequest=')
        ->and($url)->toContain('RelayState=');
});

it('builds a logout redirect url towards the idp slo endpoint', function () {
    $auth = new SamlAuth(SamlConfig::factory()->make());

    $url = $auth->logout(returnTo: 'https://app.test/login', stay: true);

    expect($url)->toStartWith('https://idp.test/saml2/slo')
        ->and($url)->toContain('SAMLRequest=');
});

it('throws a generic saml error on an invalid acs response', function () {
    $auth = new SamlAuth(SamlConfig::factory()->make());

    $_POST['SAMLResponse'] = base64_encode('<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"></samlp:Response>');

    try {
        $auth->acs();
        $this->fail('Expected a SamlError to be thrown.');
    } catch (SamlError $e) {
        expect($e->getCode())->toBe(SamlError::SAML_ACS_INVALID)
            ->and($e->getMessage())->toBe('Invalid acs response.');
    } finally {
        unset($_POST['SAMLResponse']);
    }
});

it('includes the error reason on an invalid acs response in debug mode', function () {
    $auth = new SamlAuth(SamlConfig::factory()->debug()->make());

    $_POST['SAMLResponse'] = base64_encode('<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"></samlp:Response>');

    // In debug mode OneLogin echoes the validation error, so capture it.
    ob_start();

    try {
        $auth->acs();
        $this->fail('Expected a SamlError to be thrown.');
    } catch (SamlError $e) {
        expect($e->getCode())->toBe(SamlError::SAML_ACS_INVALID)
            ->and($e->getMessage())->toContain('Invalid acs response:');
    } finally {
        ob_end_clean();
        unset($_POST['SAMLResponse']);
    }
});
