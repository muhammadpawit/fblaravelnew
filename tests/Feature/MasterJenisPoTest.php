<?php

use App\Models\MasterJenisPo;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('guests cannot access PO type forms or mutate data', function (string $method, string $action) {
    $jenisPo = MasterJenisPo::factory()->create();

    $this->{$method}(route('master.jenis-po.'.$action, $jenisPo))->assertRedirect(route('login'));

    $this->assertDatabaseCount('master_jenis_po', 1);
    $this->assertNotSoftDeleted($jenisPo);
})->with([['get', 'create'], ['get', 'edit'], ['post', 'store'], ['put', 'update'], ['delete', 'destroy']]);

test('PO type forms display stored rates and toggles', function () {
    $jenisPo = MasterJenisPo::factory()->create(['harga_packing' => '1250.75']);
    $this->actingAs(User::factory()->administrator()->create());

    $this->get(route('master.jenis-po.create'))->assertOk()->assertSee('Simpan Jenis PO');
    $this->get(route('master.jenis-po.edit', $jenisPo))->assertOk()->assertSee('1250.75')->assertSee('Status aktif')->assertSee('Tampilkan');
});

test('PO types store all finishing rates with decimal precision', function () {
    $data = MasterJenisPo::factory()->make(['perkalian' => '1.2500'])->toArray();
    foreach (['harga_packing', 'cucian_finishing', 'cucian_finishing_luar', 'buangbenang', 'lobangkancing', 'pasangkancing', 'tress'] as $field) {
        $data[$field] = '350.25';
    }

    $this->actingAs(User::factory()->administrator()->create())->post(route('master.jenis-po.store'), $data)
        ->assertRedirect(route('master.jenis-po.index'))->assertSessionHas('success');

    $jenisPo = MasterJenisPo::sole();
    expect($jenisPo->perkalian)->toBe('1.2500');
    foreach (['harga_packing', 'cucian_finishing', 'cucian_finishing_luar', 'buangbenang', 'lobangkancing', 'pasangkancing', 'tress'] as $field) {
        expect($jenisPo->{$field})->toBe('350.25');
    }
});

test('PO types can keep their own name while turning off both toggles', function () {
    $jenisPo = MasterJenisPo::factory()->create();
    $data = $jenisPo->toArray();
    $data['status'] = '0';
    $data['tampil'] = '0';
    $data['harga_packing'] = '750.50';
    $data['deleted_at'] = now()->toDateTimeString();

    $this->actingAs(User::factory()->administrator()->create())->put(route('master.jenis-po.update', $jenisPo), $data)
        ->assertRedirect(route('master.jenis-po.index'))->assertSessionHas('success');

    expect($jenisPo->fresh()->status)->toBe(0);
    expect($jenisPo->fresh()->tampil)->toBeFalse();
    expect($jenisPo->fresh()->harga_packing)->toBe('750.50');
    $this->assertNotSoftDeleted($jenisPo);
});

test('PO names remain unique including deleted records', function (bool $deleted) {
    $existing = MasterJenisPo::factory()->create(['nama_jenis_po' => 'Kemeja']);
    if ($deleted) {
        $existing->delete();
    }
    $jenisPo = MasterJenisPo::factory()->create();
    $data = MasterJenisPo::factory()->make(['nama_jenis_po' => 'Kemeja'])->toArray();
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('master.jenis-po.store'), $data)->assertSessionHasErrors('nama_jenis_po');
    $this->put(route('master.jenis-po.update', $jenisPo), $data + ['id' => $existing->id])->assertSessionHasErrors('nama_jenis_po');

    $this->assertDatabaseCount('master_jenis_po', 2);
    expect($jenisPo->fresh()->nama_jenis_po)->toBe($jenisPo->nama_jenis_po);
})->with([true, false]);

test('PO type rejects invalid values on create and update', function (string $field, mixed $value) {
    $jenisPo = MasterJenisPo::factory()->create()->refresh();
    $data = MasterJenisPo::factory()->make()->toArray();
    $data[$field] = $value;
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('master.jenis-po.store'), $data)->assertSessionHasErrors($field);
    $this->put(route('master.jenis-po.update', $jenisPo), $data)->assertSessionHasErrors($field);

    $this->assertDatabaseCount('master_jenis_po', 1);
    expect($jenisPo->fresh()->getAttributes())->toBe($jenisPo->getAttributes());
})->with([
    ['nama_jenis_po', ''], ['nama_jenis_po', str_repeat('a', 101)],
    ['perkalian', ''], ['perkalian', '0'], ['perkalian', '-1'], ['perkalian', '1.00001'], ['perkalian', '100000000'],
    ['status', '2'], ['tampil', 'yes'], ['status', null], ['tampil', null],
    ['harga_packing', 'abc'], ['harga_packing', '-1'], ['harga_packing', '1.001'], ['harga_packing', '10000000000000'],
    ['harga_packing', null], ['cucian_finishing', null], ['cucian_finishing_luar', null],
    ['buangbenang', null], ['lobangkancing', null], ['pasangkancing', null], ['tress', null],
]);

test('PO search paginates active and inactive records without deleted matches', function () {
    MasterJenisPo::factory()->count(16)->sequence(fn ($sequence) => ['nama_jenis_po' => 'Kemeja '.sprintf('%02d', $sequence->index), 'status' => 0])->create();
    MasterJenisPo::factory()->create(['nama_jenis_po' => 'Celana']);
    MasterJenisPo::factory()->create(['nama_jenis_po' => 'Kemeja dihapus'])->delete();
    $this->actingAs(User::factory()->administrator()->create());

    $this->get(route('master.jenis-po.index', ['q' => 'Kemeja']))->assertOk()->assertSee('Tidak aktif')->assertDontSee('Kemeja dihapus')
        ->assertViewHas('jenisPos', fn ($jenisPos) => $jenisPos->total() === 16 && $jenisPos->count() === 15 && str_contains($jenisPos->nextPageUrl(), 'q=Kemeja'));
    $this->get(route('master.jenis-po.index', ['q' => 'Kemeja', 'page' => 2]))->assertOk()->assertSee('Kemeja 15');
});

test('PO names are escaped in tables and empty searches display guidance', function () {
    $jenisPo = MasterJenisPo::factory()->create(['nama_jenis_po' => '<script>alert(1)</script>']);
    $this->actingAs(User::factory()->administrator()->create());

    $this->get(route('master.jenis-po.index'))->assertOk()->assertSee($jenisPo->nama_jenis_po)->assertDontSee($jenisPo->nama_jenis_po, false);
    $this->get(route('master.jenis-po.index', ['q' => 'not-found']))->assertOk()->assertSee('Tidak ada Jenis PO yang cocok');
});

test('deleting PO types preserves data and prevents editing', function () {
    $jenisPo = MasterJenisPo::factory()->create();
    $this->actingAs(User::factory()->administrator()->create());

    $this->delete(route('master.jenis-po.destroy', $jenisPo))->assertRedirect(route('master.jenis-po.index'));

    $this->assertSoftDeleted($jenisPo);
    $this->assertDatabaseCount('master_jenis_po', 1);
    $this->get(route('master.jenis-po.edit', $jenisPo))->assertNotFound();
    $this->put(route('master.jenis-po.update', $jenisPo), [])->assertNotFound();
    $this->delete(route('master.jenis-po.destroy', $jenisPo))->assertNotFound();
});

test('master search rejects malformed queries', function (string $route, mixed $query, string $message) {
    $this->actingAs(User::factory()->administrator()->create())->get(route($route, ['q' => $query]))
        ->assertSessionHasErrors(['q' => $message]);
})->with([
    ['master.cmt.index', ['array'], 'Pencarian harus berupa teks.'],
    ['master.jenis-po.index', str_repeat('a', 101), 'Pencarian maksimal 100 karakter.'],
]);
