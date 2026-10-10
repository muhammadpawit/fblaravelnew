@props(['name', 'label', 'value' => '', 'type' => 'text'])
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }} @if ($attributes->has('required'))<span aria-hidden="true" class="text-red-600">*</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
        {{ $attributes->merge(['class' => 'mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 focus:border-teal-600 focus:outline-2 focus:outline-teal-600']) }}>
    @error($name)<p id="{{ $name }}-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
