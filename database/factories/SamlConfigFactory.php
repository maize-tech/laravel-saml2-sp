<?php

namespace Maize\Saml2Sp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maize\Saml2Sp\Models\SamlConfig;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Maize\Saml2Sp\Models\SamlConfig>
 */
class SamlConfigFactory extends Factory
{
    protected $model = SamlConfig::class;

    public function definition(): array
    {
        $certs = __DIR__.'/../../tests/Fixtures/certs';

        return [
            'strict' => false,
            'debug' => false,
            'sp' => [
                'entityId' => 'https://sp.test/saml2/metadata',
                'assertionConsumerService' => [
                    'url' => 'https://sp.test/saml2/acs',
                ],
                'singleLogoutService' => [
                    'url' => 'https://sp.test/saml2/sls',
                ],
                'x509cert' => $this->readCert("{$certs}/sp.crt"),
                'privateKey' => $this->readCert("{$certs}/sp.key"),
            ],
            'idp' => [
                'entityId' => 'https://idp.test/saml2/metadata',
                'singleSignOnService' => [
                    'url' => 'https://idp.test/saml2/sso',
                ],
                'singleLogoutService' => [
                    'url' => 'https://idp.test/saml2/slo',
                ],
                'x509cert' => $this->readCert("{$certs}/idp.crt"),
            ],
            'security' => [],
            'contactPerson' => [],
            'organization' => [],
        ];
    }

    /**
     * Enable SAML strict validation on the generated config.
     */
    public function strict(): static
    {
        return $this->state(fn () => ['strict' => true]);
    }

    /**
     * Enable SAML debug mode on the generated config.
     *
     * SamlAuth reads the debug flag from the "sp" settings, so it is set there too.
     */
    public function debug(): static
    {
        return $this->state(fn (array $attributes) => [
            'debug' => true,
            'sp' => array_merge($attributes['sp'] ?? [], ['debug' => true]),
        ]);
    }

    private function readCert(string $path): string
    {
        return trim(file_get_contents($path));
    }
}
