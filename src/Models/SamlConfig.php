<?php

namespace Maize\Saml2Sp\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Maize\Saml2Sp\Support\Config;

/**
 * @property bool $strict
 * @property bool $debug
 * @property array $sp
 * @property array $idp
 * @property array $security
 * @property array $contactPerson
 * @property array $organization
 * @property array $settings
 */
class SamlConfig extends Model
{
    protected $table = 'saml_configs';

    protected $fillable = [
        'strict',
        'debug',
        'sp',
        'idp',
        'security',
        'contactPerson',
        'organization',
    ];

    protected $casts = [
        'strict' => 'boolean',
        'debug' => 'boolean',
        'sp' => 'encrypted:array',
        'idp' => 'encrypted:array',
        'security' => 'encrypted:array',
        'contactPerson' => 'encrypted:array',
        'organization' => 'encrypted:array',
    ];

    protected function settings(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => collect([
                'strict',
                'debug',
                'sp',
                'idp',
                'security',
                'contactPerson',
                'organization',
            ])->map(fn ($key) => [
                $key => Config::getDefaultSamlValue(attribute: $key, value: $attributes[$key]),
            ])->toArray()
        );
    }
}
