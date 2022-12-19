<?php

namespace Maize\Saml2Sp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SamlLogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_url' => ['sometimes', 'string'],
        ];
    }
}
