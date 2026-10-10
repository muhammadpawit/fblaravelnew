<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('guests can see the username login form', function () {
    $this->get(route('login'))->assertOk()->assertSee('name="username"', false);
});

test('users can sign in with username and the login time is recorded', function () {
    $this->travelTo(now()->startOfSecond());
    $user = User::factory()->create(['password' => 'secret-password']);

    $this->post(route('login.store'), ['username' => $user->username, 'password' => 'secret-password', 'remember' => '1'])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->last_login->equalTo(now()))->toBeTrue();
    expect($user->fresh()->last_activity->equalTo(now()))->toBeTrue();
    expect($user->fresh()->remember_token)->not->toBeNull();
});

test('invalid credentials do not authenticate or expose the password in the session', function () {
    $user = User::factory()->create();

    $this->from(route('login'))->post(route('login.store'), ['username' => $user->username, 'password' => 'wrong-password'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['username' => 'Username atau password salah.'])
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
    expect($user->fresh()->last_login)->toBeNull();
});

test('deleted users cannot sign in through the login form', function () {
    $user = User::factory()->create(['password' => 'secret-password']);
    $user->delete();

    $this->post(route('login.store'), ['username' => $user->username, 'password' => 'secret-password'])
        ->assertSessionHasErrors(['username' => 'Username atau password salah.']);

    $this->assertGuest();
});

test('login requires username and password', function () {
    $this->post(route('login.store'), [])->assertSessionHasErrors([
        'username' => 'Username wajib diisi.',
        'password' => 'Password wajib diisi.',
    ]);
});

test('login rejects malformed input', function (array $input, string $field, string $message) {
    $this->post(route('login.store'), array_replace(['username' => 'operator', 'password' => 'secret'], $input))
        ->assertSessionHasErrors([$field => $message]);
})->with([
    'username type' => [['username' => ['operator']], 'username', 'Username harus berupa teks.'],
    'username length' => [['username' => str_repeat('a', 51)], 'username', 'Username maksimal 50 karakter.'],
    'password type' => [['password' => ['secret']], 'password', 'Password harus berupa teks.'],
    'password length' => [['password' => str_repeat('a', 256)], 'password', 'Password maksimal 255 karakter.'],
    'remember type' => [['remember' => 'yes'], 'remember', 'Pilihan ingat saya tidak valid.'],
]);

test('five failed attempts block further login attempts for one minute', function () {
    $this->freezeTime();
    $user = User::factory()->create(['password' => 'secret-password']);

    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post(route('login.store'), ['username' => $user->username, 'password' => 'wrong'])
            ->assertSessionHasErrors(['username' => 'Username atau password salah.']);
    }

    $this->post(route('login.store'), ['username' => $user->username, 'password' => 'secret-password'])
        ->assertSessionHasErrors(['username' => 'Terlalu banyak percobaan. Coba lagi dalam 60 detik.']);
    $this->assertGuest();

    $this->travel(61)->seconds();
    $this->post(route('login.store'), ['username' => $user->username, 'password' => 'secret-password'])
        ->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('authenticated users are redirected away from login', function () {
    $this->actingAs(User::factory()->create())->get(route('login'))->assertRedirect(route('dashboard'));
});

test('logout invalidates the session and ends authentication', function () {
    $this->actingAs(User::factory()->create())->withSession(['private_data' => 'private']);

    $this->post(route('logout'))->assertRedirect(route('login'))->assertSessionMissing('private_data');

    $this->assertGuest();
});

test('admin seeder creates an administrator without changing it on rerun', function () {
    $this->seed(AdminUserSeeder::class);
    $admin = User::where('username', 'admin')->sole();
    $passwordHash = $admin->password;

    $this->seed(AdminUserSeeder::class);

    expect($admin->fresh()->password)->toBe($passwordHash);
    expect($admin->hak_akses)->toBe(1);
    $this->assertDatabaseCount('users', 1);
});
