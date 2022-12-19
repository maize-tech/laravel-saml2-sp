<?php

namespace Maize\Saml2Sp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SamlSlsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'RelayState' => ['sometimes', 'string'],
        ];
    }
}
