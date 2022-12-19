<?php

namespace Maize\Saml2Sp\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Saml2Sp\Casts\SamlAttributeCast;

class SamlConfig extends Model
{
    protected $table = 'saml_configs';

    protected $fillable = [
        'sp',
        'idp',
        'security',
    ];

    protected $casts = [
        'sp' => SamlAttributeCast::class,
        'idp' => SamlAttributeCast::class,
        'security' => SamlAttributeCast::class,
    ];

    public function getSettingsAttribute()
    {
        return [
            'sp' => $this->sp,
            'idp' => $this->idp,
            'security' => $this->security,
        ];
    }
}
