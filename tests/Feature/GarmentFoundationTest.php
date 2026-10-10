<?php

use App\Models\MasterCmt;
use App\Models\MasterJenisPo;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

test('users hash passwords and exclude credentials from serialization', function () {
    $user = User::factory()->make(['password' => 'garment-password']);

    expect(Hash::check('garment-password', $user->password))->toBeTrue();
    expect($user->toArray())->not->toHaveKeys(['password', 'remember_token']);
});

test('access levels cannot be changed by mass assignment', function () {
    $user = new User;
    $user->hak_akses = 2;

    $user->fill(['name' => 'Operator', 'hak_akses' => 1]);

    expect($user->hak_akses)->toBe(2);
});

test('master records retain decimal rates and bank account leading zeros', function () {
    $cmt = MasterCmt::factory()->create(['norek' => '0012345678', 'ongkos_transport_perdz' => '1250.75']);
    $jenisPo = MasterJenisPo::factory()->create(['perkalian' => '1.2500', 'harga_packing' => '350.25']);

    expect($cmt->fresh()->norek)->toBe('0012345678');
    expect($cmt->fresh()->ongkos_transport_perdz)->toBe('1250.75');
    expect($jenisPo->fresh()->perkalian)->toBe('1.2500');
    expect($jenisPo->fresh()->harga_packing)->toBe('350.25');
});

test('deleted records are hidden and can be restored', function (string $modelClass) {
    $model = $modelClass::factory()->create();

    $model->delete();

    $this->assertSoftDeleted($model);
    expect($modelClass::find($model->id))->toBeNull();

    $model->restore();

    $this->assertModelExists($modelClass::findOrFail($model->id));
})->with([User::class, MasterCmt::class, MasterJenisPo::class]);

test('deleted users cannot authenticate', function () {
    $user = User::factory()->create(['password' => 'garment-password']);
    $user->delete();

    expect(Auth::attempt(['email' => $user->email, 'password' => 'garment-password']))->toBeFalse();
});

test('purchase order type names must be unique', function () {
    MasterJenisPo::factory()->create(['nama_jenis_po' => 'Kemeja']);

    expect(fn () => MasterJenisPo::factory()->create(['nama_jenis_po' => 'Kemeja']))
        ->toThrow(UniqueConstraintViolationException::class);
});
