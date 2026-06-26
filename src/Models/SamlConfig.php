<?php

namespace Maize\Saml2Sp\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\Saml2Sp\Support\Config;

/**
 * @property string|null $key
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
    use HasFactory;

    protected $fillable = [
        'key',
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
            get: fn () => collect([
                'strict',
                'debug',
                'sp',
                'idp',
                'security',
                'contactPerson',
                'organization',
            ])->mapWithKeys(fn ($key) => [
                $key => Config::getDefaultSamlValue(attribute: $key, value: $this->$key),
            ])->toArray()
        );
    }
}
