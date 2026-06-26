<?php

use Maize\Saml2Sp\Models\SamlConfig;

it('outputs the sp metadata for the first config', function () {
    SamlConfig::factory()->create();

    $this->artisan('saml2-sp:metadata')
        ->expectsOutputToContain('EntityDescriptor')
        ->assertSuccessful();
});

it('resolves the config by its key', function () {
    SamlConfig::factory()->create(['key' => 'acme']);

    $this->artisan('saml2-sp:metadata', ['config' => 'acme'])
        ->expectsOutputToContain('EntityDescriptor')
        ->assertSuccessful();
});

it('writes the metadata to a file', function () {
    SamlConfig::factory()->create();

    $path = sys_get_temp_dir().'/saml2-sp-metadata-test.xml';

    $this->artisan('saml2-sp:metadata', ['--output' => $path])
        ->assertSuccessful();

    expect(file_get_contents($path))->toContain('EntityDescriptor');

    @unlink($path);
});

it('fails when no config exists', function () {
    $this->artisan('saml2-sp:metadata')
        ->expectsOutputToContain('No SAML config found.')
        ->assertFailed();
});
