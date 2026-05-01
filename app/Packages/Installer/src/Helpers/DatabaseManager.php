<?php

namespace App\Packages\Installer\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseManager
{
    public function isInstalled(): bool
    {
        if (! file_exists(base_path('.env'))) {
            return false;
        }

        try {
            DB::connection()->getPdo();

            if (! DB::connection()->getDatabaseName()) {
                return false;
            }

            if (! Schema::hasTable('users')) {
                return false;
            }

            return DB::table('users')->count() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function migration(): ?JsonResponse
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);

            return null;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function seeder(): void
    {
        Artisan::call('db:seed', ['--force' => true]);
        $this->storageLink();
    }

    private function storageLink(): void
    {
        try {
            Artisan::call('storage:link');
        } catch (Exception $e) {
            // Link may already exist
        }
    }
}
