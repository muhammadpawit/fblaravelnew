<?php

use App\Models\MasterCmt;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('guests cannot access CMT forms or mutate data', function (string $method, string $action) {
    $cmt = MasterCmt::factory()->create();

    $this->{$method}(route('master.cmt.'.$action, $cmt))->assertRedirect(route('login'));

    $this->assertDatabaseCount('master_cmt', 1);
    $this->assertNotSoftDeleted($cmt);
})->with([['get', 'create'], ['get', 'edit'], ['post', 'store'], ['put', 'update'], ['delete', 'destroy']]);

test('CMT forms render stored values and escaped names', function () {
    $cmt = MasterCmt::factory()->create(['cmt_name' => '<script>alert(1)</script>', 'norek' => '001234']);
    $this->actingAs(User::factory()->administrator()->create());

    $this->get(route('master.cmt.create'))->assertOk()->assertSee('Simpan CMT');
    $this->get(route('master.cmt.edit', $cmt))->assertOk()->assertSee('001234')->assertSee($cmt->cmt_name)->assertDontSee($cmt->cmt_name, false);
});

test('CMT can be created for each supported production category', function (string $category) {
    $data = MasterCmt::factory()->make(['cmt_job_desk' => $category, 'norek' => '001234', 'ongkos_transport_perdz' => '1250.75'])->toArray();

    $this->actingAs(User::factory()->administrator()->create())->post(route('master.cmt.store'), $data + ['deleted_at' => now()->toDateTimeString()])
        ->assertRedirect(route('master.cmt.index'))->assertSessionHas('success');

    $cmt = MasterCmt::sole();
    expect($cmt->norek)->toBe('001234');
    expect($cmt->ongkos_transport_perdz)->toBe('1250.75');
    expect($cmt->cmt_job_desk)->toBe($category);
    $this->assertNotSoftDeleted($cmt);
})->with(['JAHIT', 'SABLON', 'BORDIR']);

test('CMT updates only validated attributes and clears optional fields', function () {
    $cmt = MasterCmt::factory()->create(['email' => 'cmt@example.com', 'bank' => 'Bank Lama']);
    $other = MasterCmt::factory()->create();
    $data = MasterCmt::factory()->make(['cmt_name' => 'Mitra Baru', 'email' => '', 'bank' => ''])->toArray();

    $this->actingAs(User::factory()->administrator()->create())->put(route('master.cmt.update', $cmt), $data + ['id' => $other->id])
        ->assertRedirect(route('master.cmt.index'))->assertSessionHas('success');

    expect($cmt->fresh()->cmt_name)->toBe('Mitra Baru');
    expect($cmt->fresh()->email)->toBeNull();
    expect($cmt->fresh()->bank)->toBeNull();
    expect($other->fresh()->cmt_name)->toBe($other->cmt_name);
});

test('CMT rejects invalid data on create and update', function (string $field, mixed $value, string $message) {
    $cmt = MasterCmt::factory()->create()->refresh();
    $data = MasterCmt::factory()->make()->toArray();
    $data[$field] = $value;
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('master.cmt.store'), $data)->assertSessionHasErrors([$field => $message]);
    $this->put(route('master.cmt.update', $cmt), $data)->assertSessionHasErrors([$field => $message]);

    $this->assertDatabaseCount('master_cmt', 1);
    expect($cmt->fresh()->getAttributes())->toBe($cmt->getAttributes());
})->with([
    ['kode_cmt', '', 'Kode CMT wajib diisi.'],
    ['cmt_name', '', 'Nama CMT wajib diisi.'],
    ['cmt_job_desk', '', 'Kategori CMT wajib diisi.'],
    ['alamat', '', 'Alamat wajib diisi.'],
    ['telephone', '', 'Telepon wajib diisi.'],
    ['cmt_job_desk', 'POTONG', 'Kategori CMT harus JAHIT, SABLON, atau BORDIR.'],
    ['email', 'not-email', 'Email harus berupa alamat email yang valid.'],
    ['telephone', str_repeat('1', 21), 'Telepon melebihi batas maksimum 20.'],
    ['cmt_name', str_repeat('a', 101), 'Nama CMT melebihi batas maksimum 100.'],
    ['ongkos_transport_perdz', '-1', 'Ongkos transport per lusin tidak boleh negatif.'],
    ['ongkos_transport_perdz', '1.001', 'Ongkos transport per lusin maksimal 2 angka desimal.'],
    ['ongkos_transport_perdz', '10000000000000', 'Ongkos transport per lusin melebihi batas maksimum 9999999999999.99.'],
]);

test('CMT search excludes deleted matches and paginates while retaining the query', function () {
    MasterCmt::factory()->count(16)->sequence(fn ($sequence) => ['cmt_name' => 'Mitra '.sprintf('%02d', $sequence->index)])->create();
    MasterCmt::factory()->create(['cmt_name' => 'Lainnya']);
    MasterCmt::factory()->create(['cmt_name' => 'Mitra dihapus'])->delete();

    $this->actingAs(User::factory()->administrator()->create())->get(route('master.cmt.index', ['q' => 'Mitra']))
        ->assertOk()->assertSee('Mitra 00')->assertDontSee('Mitra 15')->assertDontSee('Mitra dihapus')
        ->assertViewHas('cmts', fn ($cmts) => $cmts->total() === 16 && $cmts->count() === 15 && str_contains($cmts->nextPageUrl(), 'q=Mitra'));
    $this->get(route('master.cmt.index', ['q' => 'Mitra', 'page' => 2]))->assertOk()->assertSee('Mitra 15');
});

test('CMT search and names are escaped and empty results have guidance', function () {
    $cmt = MasterCmt::factory()->create(['cmt_name' => '<script>alert(1)</script>']);
    $this->actingAs(User::factory()->administrator()->create());

    $this->get(route('master.cmt.index', ['q' => $cmt->cmt_name]))->assertOk()->assertSee($cmt->cmt_name)->assertDontSee($cmt->cmt_name, false);
    $this->get(route('master.cmt.index', ['q' => 'not-found']))->assertOk()->assertSee('Tidak ada CMT yang cocok');
});

test('deleting CMT retains its record but prevents subsequent editing', function () {
    $cmt = MasterCmt::factory()->create();
    $this->actingAs(User::factory()->administrator()->create());

    $this->delete(route('master.cmt.destroy', $cmt))->assertRedirect(route('master.cmt.index'));

    $this->assertSoftDeleted($cmt);
    $this->assertDatabaseCount('master_cmt', 1);
    $this->get(route('master.cmt.edit', $cmt))->assertNotFound();
    $this->put(route('master.cmt.update', $cmt), [])->assertNotFound();
    $this->delete(route('master.cmt.destroy', $cmt))->assertNotFound();
});
