<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Blok pengecekan environment dimatikan sementara agar bisa seeding di hosting
        // if (! app()->environment('local', 'testing')) {
        //     throw new RuntimeException('Seeder admin awal hanya untuk lingkungan local atau testing.');
        // }

        if (User::withTrashed()->where('username', 'admin')->orWhere('email', 'muhammad.pawit93@gmail.com')->exists()) {
            $this->command?->warn('Akun admin sudah ada. Password dan hak akses tidak diubah.');

            return;
        }

        $password = Str::password(20);
        $user = new User([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'muhammad.pawit93@gmail.com',
            'password' => $password,
        ]);
        $user->hak_akses = 1;
        $user->role_id = Role::where('slug', 'administrator')->firstOrFail()->id;
        $user->save();

        $this->command?->info('Username: admin');
        $this->command?->info('Password awal: '.$password);
        $this->command?->warn('Simpan password ini; menjalankan ulang seeder tidak akan menggantinya.');
    }
}
