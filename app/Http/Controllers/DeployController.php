<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DeployController extends Controller
{
    /**
     * Jalankan proses deployment (Pull otomatis) ..
     */
    public function handle(Request $request)
    {
        // Proteksi route dengan token sederhana
        // Set DEPLOY_TOKEN di .env, default: 'rahasia'
        $token = $request->input('token');
        if ($token !== env('DEPLOY_TOKEN', 'rahasia')) {
            return response()->json(['error' => 'Unauthorized. Token tidak valid.'], 403);
        }

        try {
            $output = [];

            // Daftar perintah yang akan dijalankan
            $commands = [
                'git pull origin master',          // Tarik kode terbaru
                'composer install --no-dev --optimize-autoloader', // Install dependensi
                'php artisan migrate --force',   // Jalankan migrasi
                'php artisan optimize:clear',    // Hapus cache lama
                'php artisan view:cache',        // Cache view baru
            ];

            foreach ($commands as $command) {
                // Eksekusi setiap perintah di root folder project
                $result = Process::path(base_path())->run($command);

                $output[] = [
                    'command' => $command,
                    'output' => trim($result->output()),
                    'error' => trim($result->errorOutput())
                ];
            }

            // Catat ke log laravel (storage/logs/laravel.log)
            Log::info('Auto Deploy Triggered', ['result' => $output]);

            return response()->json([
                'status' => 'success',
                'message' => 'Deployment berhasil dijalankan.',
                'details' => $output
            ]);
        } catch (\Exception $e) {
            Log::error('Auto Deploy Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Deployment gagal: ' . $e->getMessage()], 500);
        }
    }
}
