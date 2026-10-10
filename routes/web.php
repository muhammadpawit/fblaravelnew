<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MasterCmtController;
use App\Http\Controllers\MasterJenisPoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => to_route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('throttle:20,1')->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->middleware('throttle:20,1')->name('google.callback');
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('cmt', MasterCmtController::class)->except('show')
            ->middlewareFor('index', 'can:cmt.view')
            ->middlewareFor(['create', 'store'], 'can:cmt.create')
            ->middlewareFor(['edit', 'update'], 'can:cmt.update')
            ->middlewareFor('destroy', 'can:cmt.delete');
        Route::resource('jenis-po', MasterJenisPoController::class)->parameters(['jenis-po' => 'jenisPo'])->except('show')
            ->middlewareFor('index', 'can:jenis-po.view')
            ->middlewareFor(['create', 'store'], 'can:jenis-po.create')
            ->middlewareFor(['edit', 'update'], 'can:jenis-po.update')
            ->middlewareFor('destroy', 'can:jenis-po.delete');
        Route::resource('login-carousels', \App\Http\Controllers\LoginCarouselController::class)->except('show')
            ->middlewareFor('index', 'can:login-carousel.view')
            ->middlewareFor(['create', 'store'], 'can:login-carousel.create')
            ->middlewareFor(['edit', 'update'], 'can:login-carousel.update')
            ->middlewareFor('destroy', 'can:login-carousel.delete');
    });

    Route::get('/pdf-signature', [\App\Http\Controllers\PdfSignatureController::class, 'index'])->name('pdf-signature.index')->middleware('can:pdf-signature.view');
    Route::post('/pdf-signature/generate', [\App\Http\Controllers\PdfSignatureController::class, 'generatePdf'])->name('pdf-signature.generate')->middleware('can:pdf-signature.view');
});

Route::middleware(['auth', 'can:access.manage'])->prefix('access')->name('access.')->group(function () {
    Route::resource('users', UserController::class)->except('show');
    Route::resource('roles', RoleController::class)->except('show');
    Route::resource('menus', MenuController::class)->except('show');
    Route::get('permissions', PermissionController::class)->name('permissions.index');
});
