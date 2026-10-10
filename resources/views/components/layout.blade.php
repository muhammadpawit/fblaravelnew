@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} · Garment ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <div x-data="{ expanded: true }" class="mb-2">
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
            <main {{ $attributes->merge(['class' => 'mx-auto max-w-6xl p-6 md:p-10']) }}>
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
</body>
</html>
