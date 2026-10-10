<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoogleCallbackRequest extends FormRequest
{
    protected $redirectRoute = 'login';

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'code' => ['required_without:error', 'nullable', 'string', 'max:4096'],
            'state' => ['required_without:error', 'nullable', 'string', 'max:255'],
            'error' => ['nullable', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            '*.required_without' => 'Sesi login Google tidak valid. Silakan coba lagi.',
            '*.string' => 'Respons login Google tidak valid. Silakan coba lagi.',
            '*.max' => 'Respons login Google tidak valid. Silakan coba lagi.',
        ];
    }
}
