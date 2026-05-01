<?php

namespace App\Packages\Installer\Helpers;

use Exception;

class EnvironmentManager
{
    public function generateEnv(array $request): bool
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $example = base_path('.env.example');
            file_exists($example) ? copy($example, $envPath) : touch($envPath);
        }

        try {
            $this->setEnvConfiguration($request);
            $this->generateKey();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setEnvConfiguration(array $request): bool
    {
        $map = [
            'db_hostname'   => 'DB_HOST',
            'db_name'       => 'DB_DATABASE',
            'db_prefix'     => 'DB_PREFIX',
            'db_username'   => 'DB_USERNAME',
            'db_password'   => 'DB_PASSWORD',
            'db_connection' => 'DB_CONNECTION',
            'db_port'       => 'DB_PORT',
            'app_name'      => 'APP_NAME',
            'app_url'       => 'APP_URL',
            'app_timezone'  => 'APP_TIMEZONE',
        ];

        $data = file_get_contents(base_path('.env'));

        foreach ($map as $requestKey => $envKey) {
            if (! isset($request[$requestKey])) {
                continue;
            }

            $value = $request[$requestKey];

            if (preg_match('/\s/', (string) $value)) {
                $value = '"'.$value.'"';
            }

            if (preg_match("/^{$envKey}=.*/m", $data)) {
                $data = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$value}", $data);
            } else {
                $data .= "\n{$envKey}={$value}";
            }
        }

        try {
            file_put_contents(base_path('.env'), $data);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function generateKey(): void
    {
        \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
    }
}
