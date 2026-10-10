<?php

use App\Models\MasterCmt;
use App\Models\MasterJenisPo;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('viewer can use dashboard but cannot access master or administration pages', function (string $route) {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route($route))->assertForbidden();
})->with(['master.cmt.index', 'master.jenis-po.index', 'access.users.index', 'access.roles.index', 'access.menus.index', 'access.permissions.index']);

test('viewer dashboard omits restricted navigation and statistics', function () {
    $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertOk()
        ->assertDontSee('Master CMT')->assertDontSee('Master Jenis PO')->assertDontSee('Kelola akses')
        ->assertViewHas('activeCmtCount', null)->assertViewHas('activeJenisPoCount', null);
});

test('read only role sees its module but no write controls', function () {
    $role = Role::factory()->create();
    $role->permissions()->attach(Permission::where('name', 'cmt.view')->sole());
    $user = User::factory()->for($role)->create();
    $cmt = MasterCmt::factory()->create();

    $this->actingAs($user)->get(route('master.cmt.index'))->assertOk()->assertSee($cmt->cmt_name)
        ->assertDontSee('Tambah CMT')->assertDontSee(route('master.cmt.edit', $cmt), false)->assertDontSee('Master Jenis PO');
    $this->post(route('master.cmt.store'), [])->assertForbidden();
    $this->put(route('master.cmt.update', $cmt), [])->assertForbidden();
    $this->delete(route('master.cmt.destroy', $cmt))->assertForbidden();
    $this->assertNotSoftDeleted($cmt);
});

test('each master write permission is enforced at the route', function (string $module, string $model, string $method, string $action) {
    $record = $model::factory()->create();

    $this->actingAs(User::factory()->create())->{$method}(route('master.'.$module.'.'.$action, $record))
        ->assertForbidden();

    $this->assertNotSoftDeleted($record);
})->with([
    ['cmt', MasterCmt::class, 'get', 'create'], ['cmt', MasterCmt::class, 'get', 'edit'],
    ['cmt', MasterCmt::class, 'post', 'store'], ['cmt', MasterCmt::class, 'put', 'update'], ['cmt', MasterCmt::class, 'delete', 'destroy'],
    ['jenis-po', MasterJenisPo::class, 'get', 'create'], ['jenis-po', MasterJenisPo::class, 'get', 'edit'],
    ['jenis-po', MasterJenisPo::class, 'post', 'store'], ['jenis-po', MasterJenisPo::class, 'put', 'update'], ['jenis-po', MasterJenisPo::class, 'delete', 'destroy'],
]);

test('administrator can render all access management pages', function (string $route) {
    $this->actingAs(User::factory()->administrator()->create())->get(route($route))->assertOk();
})->with(['access.users.index', 'access.users.create', 'access.roles.index', 'access.roles.create', 'access.menus.index', 'access.menus.create', 'access.permissions.index']);

test('roles assign permissions and automatically include read access', function () {
    $permission = Permission::where('name', 'cmt.update')->sole();
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('access.roles.store'), ['name' => 'Tim Jahit', 'permissions' => [$permission->id], 'slug' => 'administrator', 'is_system' => true])
        ->assertRedirect(route('access.roles.index'));

    $role = Role::where('name', 'Tim Jahit')->sole();
    expect($role->slug)->not->toBe('administrator');
    expect($role->is_system)->toBeFalse();
    expect($role->permissions->pluck('name')->sort()->values()->all())->toBe(['cmt.update', 'cmt.view']);
    $this->get(route('access.roles.edit', $role))->assertOk();
    $this->put(route('access.roles.update', $role), ['name' => 'Tim Jahit'])->assertRedirect();
    expect($role->fresh()->permissions)->toHaveCount(0);
});

test('administrator role is immutable and default role cannot be deleted', function () {
    $admin = Role::where('slug', 'administrator')->sole();
    $viewer = Role::where('slug', 'viewer')->sole();
    $this->actingAs(User::factory()->administrator()->create());

    $this->put(route('access.roles.update', $admin), ['name' => 'Renamed'])->assertSessionHasErrors('name');
    $this->delete(route('access.roles.destroy', $admin))->assertSessionHasErrors('role');
    $this->delete(route('access.roles.destroy', $viewer))->assertSessionHasErrors('role');

    expect($admin->fresh()->name)->toBe('Administrator');
});

test('assigned roles are protected while unused roles may be deleted', function () {
    $role = Role::factory()->create();
    User::factory()->for($role)->create()->delete();
    $unused = Role::factory()->create();
    $this->actingAs(User::factory()->administrator()->create());

    $this->delete(route('access.roles.destroy', $role))->assertSessionHasErrors('role');
    $this->delete(route('access.roles.destroy', $unused))->assertRedirect(route('access.roles.index'));

    $this->assertModelExists($role);
    $this->assertModelMissing($unused);
});

test('administrators can create users with one role and hashed passwords', function () {
    $role = Role::where('slug', 'operator')->sole();
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('access.users.store'), [
        'name' => 'Operator Baru', 'username' => 'operator-baru', 'email' => 'operator@example.com',
        'role_id' => $role->id, 'password' => 'long-password-123', 'password_confirmation' => 'long-password-123',
        'hak_akses' => 1,
    ])->assertRedirect(route('access.users.index'));

    $user = User::where('username', 'operator-baru')->sole();
    expect($user->role_id)->toBe($role->id);
    expect(Hash::check('long-password-123', $user->password))->toBeTrue();
    expect($user->hak_akses)->toBeNull();
    $this->get(route('access.users.edit', $user))->assertOk()->assertDontSee($user->password, false);
});

test('changing user email clears Google identity and preserves omitted password', function () {
    $user = User::factory()->create(['google_id' => 'google-subject']);
    $hash = $user->password;
    $role = Role::where('slug', 'operator')->sole();
    $this->actingAs(User::factory()->administrator()->create());

    $this->put(route('access.users.update', $user), [
        'name' => $user->name, 'username' => $user->username, 'email' => 'new@example.com', 'role_id' => $role->id, 'password' => '',
    ])->assertRedirect(route('access.users.index'));

    expect($user->fresh()->google_id)->toBeNull();
    expect($user->fresh()->email_verified_at)->toBeNull();
    expect($user->fresh()->password)->toBe($hash);
    expect($user->fresh()->role_id)->toBe($role->id);
});

test('last administrator cannot be demoted or delete itself', function () {
    $user = User::factory()->administrator()->create();
    $viewer = Role::where('slug', 'viewer')->sole();
    $this->actingAs($user);

    $this->put(route('access.users.update', $user), ['name' => $user->name, 'username' => $user->username, 'email' => $user->email, 'role_id' => $viewer->id])
        ->assertSessionHasErrors('role_id');
    $this->delete(route('access.users.destroy', $user))->assertSessionHasErrors('user');

    $this->assertNotSoftDeleted($user);
    expect($user->fresh()->role->slug)->toBe('administrator');
});

test('delegated access manager cannot delete the last administrator', function () {
    $admin = User::factory()->administrator()->create();
    $role = Role::factory()->create();
    $role->permissions()->attach(Permission::where('name', 'access.manage')->sole());
    $manager = User::factory()->for($role)->create();

    $this->actingAs($manager)->delete(route('access.users.destroy', $admin))->assertSessionHasErrors('role_id');

    $this->assertNotSoftDeleted($admin);
});

test('users are soft deleted and an administrator can be demoted when another remains', function () {
    $actor = User::factory()->administrator()->create();
    $other = User::factory()->administrator()->create();
    $viewer = Role::where('slug', 'viewer')->sole();
    $this->actingAs($actor);

    $this->put(route('access.users.update', $other), ['name' => $other->name, 'username' => $other->username, 'email' => $other->email, 'role_id' => $viewer->id])->assertRedirect();
    $this->delete(route('access.users.destroy', $other))->assertRedirect();

    $this->assertSoftDeleted($other);
    $this->assertNotSoftDeleted($actor);
});

test('menus can be renamed reordered hidden and recreated without weakening permissions', function () {
    $menu = Menu::where('route_name', 'master.cmt.index')->sole();
    $this->actingAs(User::factory()->administrator()->create());
    $data = ['label' => 'Mitra Jahit', 'route_name' => $menu->route_name, 'group' => 'Produksi', 'sort_order' => 1, 'is_active' => 1, 'permission_id' => null];

    $this->put(route('access.menus.update', $menu), $data)->assertRedirect();
    $this->get(route('dashboard'))->assertOk()->assertSee('Mitra Jahit');
    expect($menu->fresh()->permission->name)->toBe('cmt.view');
    $this->put(route('access.menus.update', $menu), array_replace($data, ['is_active' => 0]))->assertRedirect();
    $this->get(route('dashboard'))->assertOk()->assertDontSee('Mitra Jahit');
    $this->delete(route('access.menus.destroy', $menu))->assertRedirect();
    $this->post(route('access.menus.store'), $data)->assertRedirect();
    $this->get(route('access.menus.edit', Menu::where('route_name', 'master.cmt.index')->sole()))->assertOk();
});

test('menu destinations must be registered pages and cannot bypass access', function () {
    $this->actingAs(User::factory()->administrator()->create())->post(route('access.menus.store'), [
        'label' => 'Bad', 'route_name' => 'https://evil.example', 'group' => 'Custom', 'sort_order' => 1, 'is_active' => 1,
    ])->assertSessionHasErrors('route_name');
});

test('access writes reject invalid roles permission ids and mismatched passwords', function () {
    $this->actingAs(User::factory()->administrator()->create());

    $this->post(route('access.roles.store'), ['name' => 'Invalid', 'permissions' => [999999]])->assertSessionHasErrors('permissions.0');
    $this->post(route('access.users.store'), [
        'name' => 'New', 'username' => 'new', 'email' => 'new@example.com', 'role_id' => 999999,
        'password' => 'long-password-123', 'password_confirmation' => 'different',
    ])->assertSessionHasErrors(['role_id', 'password']);
});

test('viewer cannot escalate through administration writes', function (string $method, string $route, string $model) {
    $record = $model::query()->firstOrFail();

    $this->actingAs(User::factory()->create())->{$method}(route($route, $record), [])->assertForbidden();
})->with([
    ['post', 'access.roles.store', Role::class], ['put', 'access.roles.update', Role::class], ['delete', 'access.roles.destroy', Role::class],
    ['post', 'access.users.store', Role::class], ['post', 'access.menus.store', Menu::class], ['put', 'access.menus.update', Menu::class], ['delete', 'access.menus.destroy', Menu::class],
]);

test('permission and menu seeds are idempotent and preserve customizations', function () {
    $role = Role::where('slug', 'operator')->sole();
    $role->permissions()->detach();
    $menu = Menu::where('route_name', 'dashboard')->sole();
    $menu->update(['label' => 'Beranda']);

    $this->seed([RoleSeeder::class, MenuSeeder::class]);
    $this->seed([RoleSeeder::class, MenuSeeder::class]);

    expect($role->fresh()->permissions)->toHaveCount(0);
    expect($menu->fresh()->label)->toBe('Beranda');
    $this->assertDatabaseCount('roles', 3);
    $this->assertDatabaseCount('permissions', 9);
    $this->assertDatabaseCount('menus', 7);
});
