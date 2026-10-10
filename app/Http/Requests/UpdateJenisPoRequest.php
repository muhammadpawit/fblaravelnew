<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateJenisPoRequest extends StoreJenisPoRequest
{
    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return array_replace(parent::rules(), [
            'nama_jenis_po' => ['required', 'string', 'max:100', Rule::unique('master_jenis_po', 'nama_jenis_po')->ignore($this->route('jenisPo'))],
        ]);
    }
}
