<?php

namespace App\Packages\Installer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\select;

class InstallSchoolApp extends Command
{
    protected $signature = 'school:install
        { --skip-env-check : Skip .env setup. }
        { --skip-admin-creation : Skip admin user creation. }';

    protected $description = 'Install the School Management Application.';

    public function handle(): void
    {
        $this->info('╔══════════════════════════════════════╗');
        $this->info('║  School Management App — Installer   ║');
        $this->info('╚══════════════════════════════════════╝');

        if (! $this->option('skip-env-check')) {
            $this->setupEnvFile();
        }

        $this->warn('Step: Generating application key…');
        $this->call('key:generate', ['--force' => true]);

        $this->warn('Step: Running database migrations…');
        $this->call('migrate:fresh', ['--force' => true]);

        $this->warn('Step: Seeding default data…');
        $this->call('db:seed', ['--force' => true]);

        $this->warn('Step: Linking storage directory…');
        $this->call('storage:link');

        $this->warn('Step: Clearing cached bootstrap files…');
        $this->call('optimize:clear');

        if (! $this->option('skip-admin-creation')) {
            $this->warn('Step: Creating administrator account…');
            $this->createAdmin();
        }

        $this->info('');
        $this->info('✅ Installation complete!');
        $this->info('   Visit: '.env('APP_URL', 'http://localhost:8000').'/login');
    }

    protected function setupEnvFile(): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $example = base_path('.env.example');
            file_exists($example) ? copy($example, $envPath) : touch($envPath);
            $this->info('.env file created.');
        } else {
            $this->info('.env file already exists — updating database settings.');
        }

        $dbConnection = select('Database connection', ['mysql', 'pgsql'], 'mysql');
        $dbHost       = text('Database host', default: env('DB_HOST', 'mysql'), required: true);
        $dbPort       = text('Database port', default: $dbConnection === 'pgsql' ? '5432' : '3306', required: true);
        $dbName       = text('Database name', required: true);
        $dbUsername   = text('Database username', default: 'root', required: true);
        $dbPassword   = password('Database password');
        $appUrl       = text('Application URL', default: env('APP_URL', 'http://localhost:8000'), required: true);

        $this->updateEnv([
            'DB_CONNECTION' => $dbConnection,
            'DB_HOST'       => $dbHost,
            'DB_PORT'       => $dbPort,
            'DB_DATABASE'   => $dbName,
            'DB_USERNAME'   => $dbUsername,
            'DB_PASSWORD'   => $dbPassword,
            'APP_URL'       => $appUrl,
        ]);

        // Reload database config at runtime
        config([
            "database.connections.{$dbConnection}.host"     => $dbHost,
            "database.connections.{$dbConnection}.port"     => $dbPort,
            "database.connections.{$dbConnection}.database" => $dbName,
            "database.connections.{$dbConnection}.username" => $dbUsername,
            "database.connections.{$dbConnection}.password" => $dbPassword,
        ]);

        DB::purge($dbConnection);

        $this->info('Database configuration saved.');
    }

    protected function createAdmin(): void
    {
        $name  = text('Admin name', default: 'Super Admin', required: true);
        $email = text('Admin email', default: 'admin@school.com', required: true);

        $adminPassword = password('Admin password');

        while (strlen($adminPassword) < 6) {
            $this->error('Password must be at least 6 characters.');
            $adminPassword = password('Admin password');
        }

        try {
            DB::table('users')->insertOrIgnore([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make($adminPassword),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign super-admin role if it exists
            try {
                $user = \App\Models\User::where('email', $email)->first();
                if ($user && \Spatie\Permission\Models\Role::where('name', 'super-admin')->exists()) {
                    $user->assignRole('super-admin');
                }
            } catch (\Exception $e) {
                // Role assignment optional
            }

            File::put(storage_path('installed'), 'Installed on '.now()->toDateTimeString());

            $this->info('Administrator account created successfully.');
            $this->info('  Email: '.$email);
        } catch (\Exception $e) {
            $this->error('Failed to create admin: '.$e->getMessage());
        }
    }

    protected function updateEnv(array $values): void
    {
        $data = file_get_contents(base_path('.env'));

        foreach ($values as $key => $value) {
            if (preg_match('/\s/', (string) $value)) {
                $value = '"'.$value.'"';
            }

            if (preg_match("/^{$key}=.*/m", $data)) {
                $data = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $data);
            } else {
                $data .= "\n{$key}={$value}";
            }
        }

        file_put_contents(base_path('.env'), $data);
    }
}
