<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMasterCmtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'kode_cmt' => ['required', 'string', 'max:50'],
            'cmt_name' => ['required', 'string', 'max:100'],
            'cmt_job_desk' => ['required', Rule::in(['JAHIT', 'SABLON', 'BORDIR'])],
            'alamat' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:50'],
            'an' => ['nullable', 'string', 'max:100'],
            'norek' => ['nullable', 'string', 'max:100'],
            'bank' => ['nullable', 'string', 'max:100'],
            'ongkos_transport_perdz' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999999.99'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'kode_cmt' => 'Kode CMT', 'cmt_name' => 'Nama CMT', 'cmt_job_desk' => 'Kategori CMT',
            'alamat' => 'Alamat', 'telephone' => 'Telepon', 'email' => 'Email',
            'an' => 'Atas nama rekening', 'norek' => 'Nomor rekening', 'bank' => 'Bank',
            'ongkos_transport_perdz' => 'Ongkos transport per lusin',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute melebihi batas maksimum :max.',
            'in' => ':attribute harus JAHIT, SABLON, atau BORDIR.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'numeric' => ':attribute harus berupa angka.',
            'decimal' => ':attribute maksimal 2 angka desimal.',
            'min' => ':attribute tidak boleh negatif.',
        ];
    }
}
