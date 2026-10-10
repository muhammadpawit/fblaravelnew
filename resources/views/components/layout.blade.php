@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} · Garment ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- DataTables CSS Tailwind -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    
    <!-- Tailwind CSS script for DataTables fix -->
    <style>
        table.dataTable { border-collapse: collapse !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; margin: 0 !important; border: none !important; }
        .dataTables_wrapper .dataTables_length select { padding-right: 2rem !important; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.25rem 0.5rem; outline: none; margin-left: 0.5rem; }
        .dataTables_wrapper .dataTables_length select { border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.25rem 0.5rem; outline: none; margin-right: 0.5rem; }
    </style>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ($.fn.dataTable) {
                $.extend(true, $.fn.dataTable.defaults, {
                    dom: '<"flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4"lf>rt<"flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4 gap-4"ip>',
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    }
                });
            }
        });
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900">
    <div class="min-h-screen md:grid md:grid-cols-[240px_1fr]">
        <aside class="bg-slate-950 p-6 text-slate-100">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight">Garment ERP</a>
            <p class="mt-1 text-sm text-slate-400">Manajemen produksi</p>
            <nav aria-label="Menu utama" class="mt-8 flex flex-col gap-2">
                @foreach ($navigationMenus as $group => $items)
                    @php
                        // Helper to recursively check for active child
                        $checkActive = function($menuItems) use (&$checkActive) {
                            foreach ($menuItems as $item) {
                                if ($item->route_name && request()->routeIs(str_replace('.index', '.*', $item->route_name))) return true;
                                if ($item->relationLoaded('children') && $item->children->isNotEmpty()) {
                                    if ($checkActive($item->children)) return true;
                                }
                            }
                            return false;
                        };
                        $isGroupActive = $checkActive($items);
                    @endphp
                    <div x-data="{ expanded: {{ $isGroupActive ? 'true' : 'false' }} }" class="mb-2">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-4 py-2 text-left">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $group }}</span>
                            <svg class="h-4 w-4 text-slate-500 transition-transform" :class="expanded ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse class="mt-1 flex flex-col gap-1">
                            @foreach ($items as $item)
                                <x-menu-item :item="$item" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>
        <div class="min-w-0">
            <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 py-4 md:px-10">
                <span class="text-sm font-medium text-slate-500">Operasional pabrik</span>
                <div class="flex items-center gap-4">
                    <span class="text-sm">{{ auth()->user()->name }} <span class="text-slate-500">· {{ auth()->user()->role?->name }}</span></span>
                    @can('access.manage')<a href="{{ route('access.users.index') }}" class="text-sm font-medium text-teal-700">Kelola akses</a>@endcan
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-teal-600">Keluar</button>
                    </form>
                </div>
            </header>
            <main {{ $attributes->merge(['class' => 'w-full p-6 md:p-10']) }}>
                <h1 class="text-3xl font-semibold tracking-tight">{{ $title }}</h1>
                @if ($errors->any())
                    <div role="alert" class="mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if (session('success'))
                    <p role="status" class="mt-6 rounded-lg border border-teal-200 bg-teal-50 p-4 text-sm text-teal-800">{{ session('success') }}</p>
                @endif
                <div class="mt-6">{{ $slot }}</div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                if (e.target.target === '_blank') return;
                const submitBtns = e.target.querySelectorAll('button[type="submit"], input[type="submit"]');
                submitBtns.forEach(btn => {
                    if (!btn.disabled) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        if (btn.tagName === 'BUTTON') {
                            const spinner = document.createElement('span');
                            spinner.innerHTML = '<svg class="animate-spin inline-block ml-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                            btn.appendChild(spinner.firstChild);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
