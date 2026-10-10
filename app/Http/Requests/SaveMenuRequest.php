<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access.manage') ?? false;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:100'],
            'route_name' => ['nullable', Rule::in(array_keys(config('access.menu_routes'))), Rule::unique('menus')->ignore($this->route('menu'))],
            'group' => ['required', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['required', 'boolean'],
            'parent_id' => ['nullable', 'exists:menus,id'],
        ];
    }
}
