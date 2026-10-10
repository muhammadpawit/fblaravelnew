@props(['item', 'level' => 0])

@php
    // Cek apakah item ini aktif (untuk styling dan auto-expand)
    $isActive = false;
    if ($item->route_name) {
        $isActive = request()->routeIs(str_replace('.index', '.*', $item->route_name));
    }
    // Jika ada children, cek apakah ada child yang aktif
    $hasActiveChild = false;
    if ($item->relationLoaded('children') && $item->children->isNotEmpty()) {
        $hasActiveChild = collect($item->children)->contains(function ($child) {
            return $child->route_name && request()->routeIs(str_replace('.index', '.*', $child->route_name));
        });
        if ($hasActiveChild) {
            $isActive = true;
        }
    }
    
    // Style padding berdasarkan level indentasi
    $paddingClass = $level === 0 ? 'px-4' : ($level === 1 ? 'pl-8 pr-4' : 'pl-12 pr-4');
@endphp

@if ($item->relationLoaded('children') && $item->children->isNotEmpty())
    <div x-data="{ expanded: {{ $hasActiveChild ? 'true' : 'false' }} }">
        <button @click="expanded = !expanded" class="flex w-full items-center justify-between {{ $paddingClass }} py-3 text-left rounded-lg text-sm font-medium hover:bg-slate-800 {{ $isActive ? 'text-white' : 'text-slate-300' }}">
            <span>{{ $item->label }}</span>
            <svg class="h-4 w-4 transition-transform" :class="expanded ? 'rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="expanded" x-collapse class="mt-1 flex flex-col gap-1">
            @foreach ($item->children as $child)
                <x-menu-item :item="$child" :level="$level + 1" />
            @endforeach
        </div>
    </div>
@else
    <a href="{{ $item->route_name ? route($item->route_name) : '#' }}"
       @if ($isActive) aria-current="page" @endif
       @class([
           "rounded-lg {$paddingClass} py-3 text-sm font-medium hover:bg-slate-800 flex items-center justify-between", 
           'bg-teal-600 text-white' => $isActive, 
           'text-slate-300' => !$isActive
       ])>
        <span>{{ $item->label }}</span>
    </a>
@endif
