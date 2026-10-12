<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMasterKodePoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'kode_po' => ['required', 'string', 'max:50', Rule::unique('master_kode_po', 'kode_po')->whereNull('deleted_at')],
            'nama_po' => ['required', 'string', 'max:100'],
            'jenis_po' => ['required', 'string', 'max:100'],
            'model_po' => ['nullable', 'string', 'max:100'],
            'tanggal_produksi' => ['required', 'date'],
            'kategori_po' => ['required', 'string', 'max:50'],
            'kode_artikel' => ['nullable', 'string', 'max:50'],
            'jenis_ukuran' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', 'max:50'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'kode_po' => 'Kode PO',
            'nama_po' => 'Nama PO',
            'jenis_po' => 'Jenis PO',
            'model_po' => 'Model PO',
            'tanggal_produksi' => 'Tanggal Produksi',
            'kategori_po' => 'Kategori PO',
            'kode_artikel' => 'Kode Artikel',
            'jenis_ukuran' => 'Jenis Ukuran',
            'type' => 'Type',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar, gunakan kode lain.',
            'date' => ':attribute harus berupa tanggal yang valid.',
        ];
    }
}
