<?php

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    config(['services.google.client_id' => 'test-client', 'services.google.client_secret' => 'test-secret']);
});

/** @param array<string, mixed> $profile */
function fakeGoogleResponses(array $profile, int $tokenStatus = 200): void
{
    Socialite::extend('google', function ($app) use ($profile, $tokenStatus): GoogleProvider {
        $handler = new MockHandler([
            new Response($tokenStatus, [], json_encode(['access_token' => 'test-token', 'token_type' => 'Bearer'])),
            new Response(200, [], json_encode($profile)),
        ]);

        return (new GoogleProvider($app['request'], 'test-client', 'test-secret', route('google.callback')))
            ->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));
    });
}

test('login offers Google only when credentials are configured', function () {
    $this->get(route('login'))->assertOk()->assertSee('Masuk dengan Google');

    config(['services.google.client_secret' => null]);
    $this->get(route('login'))->assertOk()->assertDontSee('Masuk dengan Google');
    $this->get(route('google.redirect'))->assertRedirect(route('login'))->assertSessionHasErrors('google');
});

test('Google redirect uses OAuth state and configured callback', function () {
    $response = $this->get(route('google.redirect'))->assertRedirect();
    $location = $response->headers->get('Location');
    parse_str(parse_url($location, PHP_URL_QUERY), $query);

    expect(parse_url($location, PHP_URL_HOST))->toBe('accounts.google.com');
    expect($query['state'])->toBe(session('state'))->not->toBeEmpty();
    expect($query['redirect_uri'])->toBe(route('google.callback'));
    expect($query['prompt'])->toBe('select_account');
});

test('registered users can link verified Google identity without changing permissions or passwords', function () {
    $this->travelTo(now()->startOfSecond());
    $user = User::factory()->create(['email' => 'operator@gmail.com', 'hak_akses' => 2]);
    $password = $user->password;
    fakeGoogleResponses(['sub' => 'google-123', 'email' => $user->email, 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('dashboard'))->assertSessionMissing('state');

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->google_id)->toBe('google-123');
    expect($user->fresh()->hak_akses)->toBe(2);
    expect($user->fresh()->password)->toBe($password);
    expect($user->fresh()->last_login->equalTo(now()))->toBeTrue();
    $this->assertDatabaseCount('users', 1);
});

test('verified Workspace users can link their existing ERP account', function () {
    $user = User::factory()->create(['email' => 'operator@garment.example']);
    fakeGoogleResponses(['sub' => 'workspace-123', 'email' => $user->email, 'email_verified' => true, 'hd' => 'garment.example']);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('linked Google users can sign in again', function () {
    $user = User::factory()->create(['email' => 'operator@gmail.com', 'google_id' => 'google-123']);
    fakeGoogleResponses(['sub' => 'google-123', 'email' => $user->email, 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('Google cannot register an unknown account', function () {
    fakeGoogleResponses(['sub' => 'unknown', 'email' => 'unknown@gmail.com', 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
    $this->assertDatabaseCount('users', 0);
});

test('Google rejects unverified or incomplete identities', function (array $overrides) {
    $user = User::factory()->create(['email' => 'operator@gmail.com']);
    fakeGoogleResponses(array_replace(['sub' => 'google-123', 'email' => $user->email, 'email_verified' => true], $overrides));

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
    expect($user->fresh()->google_id)->toBeNull();
})->with([
    'unverified' => [['email_verified' => false]],
    'missing verification' => [['email_verified' => null]],
    'missing subject' => [['sub' => null]],
    'missing email' => [['email' => null]],
]);

test('Google cannot revive deleted users', function (bool $linked) {
    $user = User::factory()->create(['email' => 'operator@gmail.com', 'google_id' => $linked ? 'google-123' : null]);
    $user->delete();
    fakeGoogleResponses(['sub' => 'google-123', 'email' => $user->email, 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
    $this->assertSoftDeleted($user);
})->with([true, false]);

test('Google cannot replace an existing linked identity', function () {
    $user = User::factory()->create(['email' => 'operator@gmail.com', 'google_id' => 'original-id']);
    fakeGoogleResponses(['sub' => 'other-id', 'email' => $user->email, 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
    expect($user->fresh()->google_id)->toBe('original-id');
});

test('Google cannot link external email without current ownership assurance', function () {
    $user = User::factory()->create(['email' => 'operator@yahoo.com']);
    fakeGoogleResponses(['sub' => 'google-123', 'email' => $user->email, 'email_verified' => true]);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
    expect($user->fresh()->google_id)->toBeNull();
});

test('OAuth state mismatch prevents login', function () {
    fakeGoogleResponses(['sub' => 'google-123', 'email' => 'operator@gmail.com', 'email_verified' => true]);

    $this->withSession(['state' => 'original-state'])->get(route('google.callback', ['state' => 'wrong-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google');

    $this->assertGuest();
});

test('cancelled consent and malformed callbacks return to login', function (array $query) {
    $this->get(route('google.callback', $query))->assertRedirect(route('login'))->assertSessionHasErrors();

    $this->assertGuest();
})->with([
    'cancelled' => [['error' => 'access_denied']],
    'missing data' => [[]],
    'invalid code' => [['code' => ['bad'], 'state' => 'test']],
]);

test('provider failures return a safe login error', function () {
    fakeGoogleResponses([], 500);

    $this->withSession(['state' => 'valid-state'])->get(route('google.callback', ['state' => 'valid-state', 'code' => 'valid-code']))
        ->assertRedirect(route('login'))->assertSessionHasErrors('google')->assertSessionMissing('_old_input.code');

    $this->assertGuest();
});
