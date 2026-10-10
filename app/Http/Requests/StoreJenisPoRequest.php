<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJenisPoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $tariff = ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999999.99'];

        return [
            'nama_jenis_po' => ['required', 'string', 'max:100', Rule::unique('master_jenis_po', 'nama_jenis_po')],
            'perkalian' => ['required', 'numeric', 'decimal:0,4', 'gt:0', 'max:99999999.9999'],
            'harga_packing' => $tariff,
            'cucian_finishing' => $tariff,
            'cucian_finishing_luar' => $tariff,
            'buangbenang' => $tariff,
            'lobangkancing' => $tariff,
            'pasangkancing' => $tariff,
            'tress' => $tariff,
            'status' => ['required', 'boolean'],
            'tampil' => ['required', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'nama_jenis_po' => 'Nama Jenis PO', 'perkalian' => 'Faktor perkalian',
            'harga_packing' => 'Harga packing', 'cucian_finishing' => 'Cucian finishing',
            'cucian_finishing_luar' => 'Cucian finishing luar', 'buangbenang' => 'Buang benang',
            'lobangkancing' => 'Lubang kancing', 'pasangkancing' => 'Pasang kancing',
            'tress' => 'Tress', 'status' => 'Status aktif', 'tampil' => 'Tampilkan',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute melebihi batas maksimum :max.',
            'unique' => ':attribute sudah digunakan, termasuk pada data yang dihapus.',
            'numeric' => ':attribute harus berupa angka.',
            'decimal' => ':attribute maksimal 2 angka desimal.',
            'perkalian.decimal' => 'Faktor perkalian maksimal 4 angka desimal.',
            'gt' => ':attribute harus lebih besar dari nol.',
            'min' => ':attribute tidak boleh negatif.',
            'boolean' => ':attribute harus aktif atau tidak aktif.',
        ];
    }
}
