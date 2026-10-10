<?php

use App\Models\MasterCmt;
use App\Models\MasterJenisPo;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('guests must sign in before accessing admin pages', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with(['dashboard', 'master.cmt.index', 'master.jenis-po.index']);

test('dashboard counts only active master records', function () {
    MasterCmt::factory()->count(2)->create();
    MasterCmt::factory()->create()->delete();
    MasterJenisPo::factory()->create(['status' => 1, 'tampil' => true]);
    MasterJenisPo::factory()->create(['status' => 0, 'tampil' => true]);
    MasterJenisPo::factory()->create(['status' => 1, 'tampil' => false]);
    MasterJenisPo::factory()->create(['status' => 1, 'tampil' => true])->delete();

    $this->actingAs(User::factory()->administrator()->create())->get(route('dashboard'))
        ->assertOk()->assertViewHas('activeCmtCount', 2)->assertViewHas('activeJenisPoCount', 1)
        ->assertSee('Master CMT')->assertSee('Master Jenis PO');
});

test('dashboard displays zeros for empty master data and escapes user names', function () {
    $user = User::factory()->administrator()->create(['name' => '<script>alert(1)</script>']);

    $this->actingAs($user)->get(route('dashboard'))->assertOk()
        ->assertViewHas('activeCmtCount', 0)->assertViewHas('activeJenisPoCount', 0)
        ->assertSee($user->name)->assertDontSee($user->name, false);
});

test('master menu pages provide data management', function (string $route) {
    $this->actingAs(User::factory()->administrator()->create())->get(route($route))
        ->assertOk()->assertSee('Tambah')->assertSee('Cari');
})->with(['master.cmt.index', 'master.jenis-po.index']);
