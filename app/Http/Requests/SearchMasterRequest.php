<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchMasterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return ['q' => ['nullable', 'string', 'max:100']];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['q.string' => 'Pencarian harus berupa teks.', 'q.max' => 'Pencarian maksimal 100 karakter.'];
    }
}
