<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · Garment ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex min-h-screen bg-white font-sans text-slate-900">
    <div class="flex w-full flex-col lg:flex-row">
        <!-- Left Side: Carousel -->
        <div class="relative hidden w-full lg:block lg:w-1/2 h-screen overflow-hidden" x-data="{
                activeSlide: 1,
                slides: {{ $carousels->count() }},
                init() {
                    setInterval(() => {
                        this.activeSlide = this.activeSlide === this.slides ? 1 : this.activeSlide + 1;
                    }, 5000);
                }
            }">
            
            @foreach($carousels as $index => $carousel)
            <div 
                class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                x-show="activeSlide === {{ $index + 1 }}"
                x-transition:enter="transition-opacity ease-linear duration-500"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <img src="{{ $carousel->image }}" class="h-full w-full object-cover" alt="Carousel image">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center p-12 text-center text-white">
                    <h2 class="text-4xl font-bold mb-4 drop-shadow-md">{{ $carousel->title }}</h2>
                    <p class="text-lg max-w-xl drop-shadow-sm">{{ $carousel->subtitle }}</p>
                </div>
            </div>
            @endforeach

            <!-- Carousel Indicators -->
            <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-2 z-10">
                @foreach($carousels as $index => $carousel)
                <button 
                    @click="activeSlide = {{ $index + 1 }}"
                    class="h-2 w-2 rounded-full transition-all"
                    :class="activeSlide === {{ $index + 1 }} ? 'bg-white w-4' : 'bg-white/50'"
                ></button>
                @endforeach
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex w-full flex-col items-center justify-center p-8 lg:w-1/2">
            <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl border border-gray-100">
                <!-- Logo Placehoder -->
                <div class="flex flex-col items-center mb-8">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full border-2 border-amber-500 text-amber-500 mb-2">
                        <span class="text-2xl font-bold">FB</span>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 tracking-widest uppercase">Truly Fashionable</span>
                </div>

                @if ($googleAvailable)
                    <div class="mb-4 text-center rounded-lg bg-slate-50 border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-800">Masuk dengan Google</h3>
                        <p class="text-xs text-slate-500 mt-1">Gunakan akun Google yang sudah terdaftar untuk mengakses sistem dengan aman dan cepat.</p>
                    </div>

                    <a href="{{ route('google.redirect') }}" 
                       x-data="{ isLoading: false }" 
                       @click="isLoading = true"
                       class="mt-4 flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-medium hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600 shadow-sm transition-colors"
                       :class="{ 'opacity-75 cursor-not-allowed': isLoading }"
                    >
                        <!-- Google Icon (shown when not loading) -->
                        <svg x-show="!isLoading" class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        
                        <!-- Spinner (shown when loading) -->
                        <svg x-show="isLoading" class="w-5 h-5 mr-3 animate-spin text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        
                        <span x-text="isLoading ? 'Memproses...' : 'Lanjutkan dengan Google'"></span>
                    </a>
                    
                    <p class="mt-4 text-center text-xs text-slate-500">
                        <span class="inline-block mr-1">🔒</span> Login aman menggunakan Google OAuth
                    </p>
                @endif
                
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <p class="text-center text-xs text-slate-400">© 2020 - {{ date('Y') }} Forboys Production</p>
                </div>
            </div>
            
            <div class="mt-6 text-center text-xs text-slate-400">
                Versi 2.1.1.0
            </div>
        </div>
    </div>
</body>
</html>
