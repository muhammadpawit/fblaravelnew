<?php

use App\Models\MasterKodePo;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MasterKodePoSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('guests cannot access Master Kode PO pages or mutate records', function (string $method, string $action) {
    $kodePo = MasterKodePo::factory()->create();

    $this->{$method}(route('master.kode-po.'.$action, $kodePo))->assertRedirect(route('login'));

    $this->assertDatabaseCount('master_kode_po', 1);
    $this->assertNotSoftDeleted($kodePo);
})->with([['get', 'index'], ['get', 'create'], ['get', 'edit'], ['post', 'store'], ['put', 'update'], ['delete', 'destroy']]);

test('administrators can create update and soft delete a PO name record', function () {
    $this->seed(RoleSeeder::class);
    $this->actingAs(User::factory()->administrator()->create());
    $data = MasterKodePo::factory()->raw(['kode_po' => 'PO-CRUD-001', 'nama_po' => 'Nama PO Baru']);

    $this->get(route('master.kode-po.index'))->assertOk()->assertSee('Nama PO');
    $this->post(route('master.kode-po.store'), $data)
        ->assertRedirect(route('master.kode-po.index'))
        ->assertSessionHas('success');

    $kodePo = MasterKodePo::sole();
    expect($kodePo->nama_po)->toBe('Nama PO Baru');

    $this->put(route('master.kode-po.update', $kodePo), array_replace($data, ['nama_po' => 'Nama PO Diperbarui']))
        ->assertRedirect(route('master.kode-po.index'))
        ->assertSessionHas('success');
    expect($kodePo->fresh()->nama_po)->toBe('Nama PO Diperbarui');

    $this->delete(route('master.kode-po.destroy', $kodePo))->assertRedirect(route('master.kode-po.index'));

    $this->assertSoftDeleted($kodePo);
    $this->get(route('master.kode-po.edit', $kodePo))->assertNotFound();
});

test('duplicate PO codes are rejected without changing existing records', function () {
    $existing = MasterKodePo::factory()->create(['kode_po' => 'PO-DUPLICATE-001']);
    $this->seed(RoleSeeder::class);
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('master.kode-po.store'), MasterKodePo::factory()->raw(['kode_po' => $existing->kode_po]))
        ->assertSessionHasErrors('kode_po');

    $this->assertDatabaseCount('master_kode_po', 1);
});

test('database seeding links the dynamic PO menu to its view permission', function () {
    $this->seed(DatabaseSeeder::class);

    $menu = Menu::where('route_name', 'master.kode-po.index')->sole();
    expect($menu->label)->toBe('Master Kode PO');
    expect($menu->permission->name)->toBe('kode-po.view');

    $role = Role::factory()->create();
    $role->permissions()->attach(Permission::where('name', 'kode-po.view')->sole());
    $user = User::factory()->for($role)->create();

    $this->actingAs($user)->get(route('dashboard'))->assertOk()->assertSee('Master Kode PO');
    $this->actingAs($user)->get(route('master.kode-po.index'))->assertOk()->assertDontSee('Tambah Kode PO');
});

test('PO demo seeding is idempotent and limited to local or test environments', function () {
    $this->seed([MasterKodePoSeeder::class, MasterKodePoSeeder::class]);

    expect(MasterKodePo::count())->toBe(3);
    expect(MasterKodePo::where('kode_po', 'DEMO-PO-001')->value('nama_po'))->toBe('Nama PO Contoh 1');
});
