@props(['name', 'label', 'value' => true])
<div>
    <input type="hidden" name="{{ $name }}" value="0">
    <label class="inline-flex cursor-pointer items-center gap-3">
        <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $value)) class="peer sr-only">
        <span aria-hidden="true" class="relative h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-teal-600 peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-teal-600 after:absolute after:top-1 after:left-1 after:size-4 after:rounded-full after:bg-white after:transition-transform peer-checked:after:translate-x-5"></span>
        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
    </label>
    @error($name)<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
</div>
