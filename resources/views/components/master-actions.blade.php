@props(['edit', 'destroy', 'label', 'editPermission' => 'access.manage', 'deletePermission' => 'access.manage'])
<div class="flex items-center justify-end gap-3">
    @can($editPermission)
    <a href="{{ $edit }}" aria-label="Ubah {{ $label }}" class="rounded px-2 py-1 font-medium text-teal-700 hover:bg-teal-50">Ubah</a>
    @endcan
    @can($deletePermission)
    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm('Hapus data ini dari daftar? Data tetap tersimpan sebagai arsip.');">
        @csrf
        @method('DELETE')
        <button type="submit" aria-label="Hapus {{ $label }}" class="rounded px-2 py-1 font-medium text-red-700 hover:bg-red-50">Hapus</button>
    </form>
    @endcan
</div>
